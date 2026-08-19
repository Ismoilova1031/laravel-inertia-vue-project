import DashboardController from './DashboardController'
import CourseController from './CourseController'
import LessonController from './LessonController'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    CourseController: Object.assign(CourseController, CourseController),
    LessonController: Object.assign(LessonController, LessonController),
}

export default Controllers