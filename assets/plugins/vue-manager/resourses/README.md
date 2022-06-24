# crm-vue3

### Изменения в node_modules перед запуском
В файле `node_modules/@vue/runtime-core/dist/runtime-core.esm-bundler.js`
Добавить проверку по ключу для KeepAlive

Строка 2464
```js
const name = vnode.key || getComponentName(vnode.type);
```

Строка 2472
```js
if (!current || cached.key !== current.key || cached.type !== current.type) {
  unmount(cached);
}
```

Строка 2539
```js
const name = vnode.key || getComponentName(isAsyncWrapper(vnode)
    ? vnode.type.__asyncResolved || {}
    : comp);
```

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
