import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\LessonController::reorder
* @see app/Http/Controllers/LessonController.php:54
* @route '/courses/{course}/lessons/reorder'
*/
export const reorder = (args: { course: string | number } | [course: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: reorder.url(args, options),
    method: 'put',
})

reorder.definition = {
    methods: ["put"],
    url: '/courses/{course}/lessons/reorder',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\LessonController::reorder
* @see app/Http/Controllers/LessonController.php:54
* @route '/courses/{course}/lessons/reorder'
*/
reorder.url = (args: { course: string | number } | [course: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { course: args }
    }

    if (Array.isArray(args)) {
        args = {
            course: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: args.course,
    }

    return reorder.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::reorder
* @see app/Http/Controllers/LessonController.php:54
* @route '/courses/{course}/lessons/reorder'
*/
reorder.put = (args: { course: string | number } | [course: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: reorder.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:24
* @route '/courses/{course}/lessons/create'
*/
export const create = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/courses/{course}/lessons/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:24
* @route '/courses/{course}/lessons/create'
*/
create.url = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { course: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { course: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            course: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.id
        : args.course,
    }

    return create.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:24
* @route '/courses/{course}/lessons/create'
*/
create.get = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:24
* @route '/courses/{course}/lessons/create'
*/
create.head = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LessonController::store
* @see app/Http/Controllers/LessonController.php:36
* @route '/courses/{course}/lessons'
*/
export const store = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/courses/{course}/lessons',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\LessonController::store
* @see app/Http/Controllers/LessonController.php:36
* @route '/courses/{course}/lessons'
*/
store.url = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { course: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { course: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            course: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: typeof args.course === 'object'
        ? args.course.id
        : args.course,
    }

    return store.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::store
* @see app/Http/Controllers/LessonController.php:36
* @route '/courses/{course}/lessons'
*/
store.post = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

const LessonController = { reorder, create, store }

export default LessonController