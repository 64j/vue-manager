export default function diff (a, b) {
  a = Object.assign(Object.create(null), a || {})
  b = Object.assign(Object.create(null), b || {})

  Object.entries(a).sort().reduce((c, [k, v]) => ({
    ...c,
    [k]: v
  }), {})

  Object.entries(b).sort().reduce((c, [k, v]) => ({
    ...c,
    [k]: v
  }), {})

  return JSON.stringify(a) === JSON.stringify(b)
}
