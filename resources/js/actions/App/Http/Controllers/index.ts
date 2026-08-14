import DashboardController from './DashboardController'
import CourseController from './CourseController'

const Controllers = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    CourseController: Object.assign(CourseController, CourseController),
}

export default Controllers