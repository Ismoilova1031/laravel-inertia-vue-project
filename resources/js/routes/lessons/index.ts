import { queryParams, type RouteQueryOptions, type RouteDefinition, applyUrlDefaults } from './../../wayfinder'
/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:21
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
* @see app/Http/Controllers/LessonController.php:21
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
* @see app/Http/Controllers/LessonController.php:21
* @route '/courses/{course}/lessons/create'
*/
create.get = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LessonController::create
* @see app/Http/Controllers/LessonController.php:21
* @route '/courses/{course}/lessons/create'
*/
create.head = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LessonController::store
* @see app/Http/Controllers/LessonController.php:33
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
* @see app/Http/Controllers/LessonController.php:33
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
* @see app/Http/Controllers/LessonController.php:33
* @route '/courses/{course}/lessons'
*/
store.post = (args: { course: number | { id: number } } | [course: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\LessonController::show
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
export const show = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/courses/{course}/lessons/{lesson}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LessonController::show
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
show.url = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: args.course,
        lesson: args.lesson,
    }

    return show.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::show
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
show.get = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LessonController::show
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
show.head = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LessonController::edit
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}/edit'
*/
export const edit = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/courses/{course}/lessons/{lesson}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\LessonController::edit
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}/edit'
*/
edit.url = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: args.course,
        lesson: args.lesson,
    }

    return edit.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::edit
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}/edit'
*/
edit.get = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\LessonController::edit
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}/edit'
*/
edit.head = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\LessonController::update
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
export const update = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/courses/{course}/lessons/{lesson}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\LessonController::update
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
update.url = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: args.course,
        lesson: args.lesson,
    }

    return update.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::update
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
update.put = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\LessonController::update
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
update.patch = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\LessonController::destroy
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
export const destroy = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/courses/{course}/lessons/{lesson}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\LessonController::destroy
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
destroy.url = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions) => {
    if (Array.isArray(args)) {
        args = {
            course: args[0],
            lesson: args[1],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        course: args.course,
        lesson: args.lesson,
    }

    return destroy.definition.url
            .replace('{course}', parsedArgs.course.toString())
            .replace('{lesson}', parsedArgs.lesson.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\LessonController::destroy
* @see app/Http/Controllers/LessonController.php:0
* @route '/courses/{course}/lessons/{lesson}'
*/
destroy.delete = (args: { course: string | number, lesson: string | number } | [course: string | number, lesson: string | number ], options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

const lessons = {
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default lessons