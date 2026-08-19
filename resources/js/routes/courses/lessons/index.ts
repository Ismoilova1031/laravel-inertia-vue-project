import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../../wayfinder'
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

const lessons = {
    reorder: Object.assign(reorder, reorder),
}

export default lessons