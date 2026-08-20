export const TaskTypes = [
    { title: 'Quiz', value: 1 },
    { title: 'File', value: 2 },
    { title: 'Discussion', value: 3 },
] as const

export type TaskType = typeof TaskTypes[number]['value']