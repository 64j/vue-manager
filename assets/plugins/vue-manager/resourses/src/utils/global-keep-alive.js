import { callWithAsyncErrorHandling, cloneVNode, getCurrentInstance, onBeforeUnmount, onMounted, onUpdated, setTransitionHooks, warn, watch } from 'vue'
import { isFunction, isString } from '@vue/shared'

let activePostFlushCbs = null
let postFlushIndex = 0
const pendingPostFlushCbs = []

const isArray = Array.isArray
const queuePostRenderEffect = queueEffectWithSuspense
const isAsyncWrapper = (i) => !!i.type.__asyncLoader

const invokeArrayFns = (fns, arg) => {
  for (let i = 0; i < fns.length; i++) {
    fns[i](arg)
  }
}

function invokeVNodeHook (hook, instance, vnode, prevVNode = null) {
  callWithAsyncErrorHandling(hook, instance, 7 /* VNODE_HOOK */, [
    vnode,
    prevVNode
  ])
}

function queueEffectWithSuspense (fn, suspense) {
  if (suspense && suspense.pendingBranch) {
    if (isArray(fn)) {
      suspense.effects.push(...fn)
    } else {
      suspense.effects.push(fn)
    }
  } else {
    queuePostFlushCb(fn)
  }
}

function queuePostFlushCb (cb) {
  queueCb(cb, activePostFlushCbs, pendingPostFlushCbs, postFlushIndex)
}

function queueCb (cb, activeQueue, pendingQueue, index) {
  if (!isArray(cb)) {
    if (!activeQueue ||
      !activeQueue.includes(cb, cb.allowRecurse ? index + 1 : index)) {
      pendingQueue.push(cb)
    }
  } else {
    // if cb is an array, it is a component lifecycle hook which can only be
    // triggered by a job, which is already deduped in the main queue, so
    // we can skip duplicate check here to improve perf
    pendingQueue.push(...cb)
  }
  //queueFlush()
}

function resetShapeFlag (vnode) {
  let shapeFlag = vnode.shapeFlag
  if (shapeFlag & 256 /* COMPONENT_SHOULD_KEEP_ALIVE */) {
    shapeFlag -= 256 /* COMPONENT_SHOULD_KEEP_ALIVE */
  }
  if (shapeFlag & 512 /* COMPONENT_KEPT_ALIVE */) {
    shapeFlag -= 512 /* COMPONENT_KEPT_ALIVE */
  }
  vnode.shapeFlag = shapeFlag
}

function isVNode (value) {
  return value ? value.__v_isVNode === true : false
}

function getInnerChild (vnode) {
  return vnode.shapeFlag & 128 /* SUSPENSE */ ? vnode.ssContent : vnode
}

function getComponentName (Component) {
  return isFunction(Component)
    ? Component.displayName || Component.name
    : Component.name
}

function matches (pattern, name) {
  if (isArray(pattern)) {
    return pattern.some((p) => matches(p, name))
  } else if (isString(pattern)) {
    return pattern.split(',').includes(name)
  } else if (pattern.test) {
    return pattern.test(name)
  }
  /* istanbul ignore next */
  return false
}

let GlobalKeepAlive = {
  name: `GlobalKeepAlive`,
  __isKeepAlive: true,
  props: {
    include: [String, RegExp, Array],
    exclude: [String, RegExp, Array],
    max: [String, Number]
  },
  methods: {
    remove (key) {
      this.$.cache.delete(key)
      this.$.keys.delete(key)
    }
  },
  setup (props, { slots }) {
    const instance = getCurrentInstance()
    const sharedContext = instance.ctx

    if (!sharedContext.renderer) {
      return slots.default()
    }

    instance.cache = new Map()
    instance.keys = new Set()
    let current = null

    const parentSuspense = instance.suspense
    const { renderer: { p: patch, m: move, um: _unmount, o: { createElement } } } = sharedContext
    const storageContainer = createElement('div')
    sharedContext.activate = (vnode, container, anchor, isSVG, optimized) => {
      const instance = vnode.component
      move(vnode, container, anchor, 0 /* ENTER */, parentSuspense)
      patch(instance.vnode, vnode, container, anchor, instance, parentSuspense, isSVG, vnode.slotScopeIds, optimized)
      queuePostRenderEffect(() => {
        instance.isDeactivated = false
        if (instance.a) {
          invokeArrayFns(instance.a)
        }
        const vnodeHook = vnode.props && vnode.props.onVnodeMounted
        if (vnodeHook) {
          invokeVNodeHook(vnodeHook, instance.parent, vnode)
        }
      }, parentSuspense)
    }
    sharedContext.deactivate = (vnode) => {
      const instance = vnode.component
      move(vnode, storageContainer, null, 1 /* LEAVE */, parentSuspense)
      queuePostRenderEffect(() => {
        if (instance.da) {
          invokeArrayFns(instance.da)
        }
        const vnodeHook = vnode.props && vnode.props.onVnodeUnmounted
        if (vnodeHook) {
          invokeVNodeHook(vnodeHook, instance.parent, vnode)
        }
        instance.isDeactivated = true
      }, parentSuspense)
    }

    function unmount (vnode) {
      // reset the shapeFlag so it can be properly unmounted
      resetShapeFlag(vnode)
      _unmount(vnode, instance, parentSuspense, true)
    }

    function pruneCache (filter) {
      instance.cache.forEach((vnode, key) => {
        const name = vnode.key || getComponentName(vnode.type)
        if (name && (!filter || !filter(name))) {
          pruneCacheEntry(key)
        }
      })
    }

    function pruneCacheEntry (key) {
      const cached = instance.cache.get(key)
      if (!current || cached.type !== current.type) {
        unmount(cached)
      } else if (current) {
        // current active instance should no longer be kept-alive.
        // we can't unmount it now but it might be later, so reset its flag now.
        resetShapeFlag(current)
      }
      instance.cache.delete(key)
      instance.keys.delete(key)
    }

    // prune cache on include/exclude prop change
    watch(() => [props.include, props.exclude], ([include, exclude]) => {
        include && pruneCache(name => matches(include, name))
        exclude && pruneCache(name => !matches(exclude, name))
      },
      // prune post-render after `current` has been updated
      { flush: 'post', deep: true })

    instance.pendingCacheKey = null
    const cacheSubtree = () => {
      // fix #1621, the pendingCacheKey could be 0
      if (instance.pendingCacheKey != null) {
        instance.cache.set(instance.pendingCacheKey, getInnerChild(instance.subTree))
      }
    }
    onMounted(cacheSubtree)
    onUpdated(cacheSubtree)
    onBeforeUnmount(() => {
      instance.cache.forEach(cached => {
        const { subTree, suspense } = instance
        const vnode = getInnerChild(subTree)
        if (cached.type === vnode.type) {
          // current instance will be unmounted as part of keep-alive's unmount
          resetShapeFlag(vnode)
          // but invoke its deactivated hook here
          const da = vnode.component.da
          da && queuePostRenderEffect(da, suspense)
          return
        }
        unmount(cached)
      })
    })

    return () => {
      instance.pendingCacheKey = null
      if (!slots.default) {
        return null
      }
      const children = slots.default()
      const rawVNode = children[0]
      if (children.length > 1) {
        {
          warn(`KeepAlive should contain exactly one component child.`)
        }
        current = null
        return children
      } else if (!isVNode(rawVNode) ||
        (!(rawVNode.shapeFlag & 4 /* STATEFUL_COMPONENT */) &&
          !(rawVNode.shapeFlag & 128 /* SUSPENSE */))) {
        current = null
        return rawVNode
      }
      let vnode = getInnerChild(rawVNode)
      const comp = vnode.type
      const name = vnode.key || getComponentName(isAsyncWrapper(vnode)
        ? vnode.type.__asyncResolved || {}
        : comp)
      const { include, exclude, max } = props
      if ((include && (!name || !matches(include, name))) ||
        (exclude && name && matches(exclude, name))) {
        current = vnode
        return rawVNode
      }
      const key = vnode.key == null ? comp : vnode.key
      const cachedVNode = instance.cache.get(key)
      if (vnode.el) {
        vnode = cloneVNode(vnode)
        if (rawVNode.shapeFlag & 128 /* SUSPENSE */) {
          rawVNode.ssContent = vnode
        }
      }
      instance.pendingCacheKey = key
      if (cachedVNode) {
        vnode.el = cachedVNode.el
        vnode.component = cachedVNode.component
        if (vnode.transition) {
          setTransitionHooks(vnode, vnode.transition)
        }
        // avoid vnode being mounted as fresh
        vnode.shapeFlag |= 512 /* COMPONENT_KEPT_ALIVE */
        // make this key the freshest
        instance.keys.delete(key)
        instance.keys.add(key)
      } else {
        instance.keys.add(key)
        if (max && instance.keys.size > parseInt(max, 10)) {
          pruneCacheEntry(instance.keys.values().next().value)
        }
      }
      vnode.shapeFlag |= 256 /* COMPONENT_SHOULD_KEEP_ALIVE */
      return rawVNode
    }
  }
}

export default GlobalKeepAlive
