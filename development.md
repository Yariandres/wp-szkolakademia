## Development Process for WordPress Theme

## Phase 1: Foundation & MVP

### Goal: Set up the basic structure and ensure the theme is functional with minimal features.

#### 1- Folder Structure Setup

Run the generate-theme-structure.sh script to create the theme structure.
Verify the directory and file hierarchy.

#### 2- Basic WordPress Integration

Add theme metadata in style.css (e.g., theme name, author, version).
Register the theme with WordPress by ensuring index.php and style.css exist.
Activate the theme in the WordPress admin panel.

#### 3- Core Functionalities

Enqueue styles and scripts in enqueue.php and load them via functions.php.
Add comprehensive theme support:

```php
// Add essential theme supports
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('custom-logo', [
    'height'      => 100,
    'width'       => 400,
    'flex-height' => true,
    'flex-width'  => true,
]);
add_theme_support('html5', [
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script'
]);
add_theme_support('woocommerce'); // Required for course purchases
add_theme_support('custom-header');
add_theme_support('automatic-feed-links');
```

Register navigation menus:

```php
register_nav_menus([
    'primary' => __('Primary Menu', 'szkolakademia'),
    'footer' => __('Footer Menu', 'szkolakademia'),
    'dashboard' => __('Dashboard Menu', 'szkolakademia'), // For logged-in users
    'courses' => __('Courses Menu', 'szkolakademia'), // For course categories
]);
```

Set content width for proper scaling:

```php
if (!isset($content_width)) {
    $content_width = 1200; // Pixels
}
```

#### 4- MVP Templates

Create basic templates: header.php, footer.php, index.php, and page.php.
Add a simple homepage layout with placeholder content.

#### 5- Test Foundation

Verify the theme loads without errors.
Test the homepage and ensure basic styling and scripts are applied.

## Phase 2: Course Management

### Goal: Add core course functionalities and integrate the course listing and detail pages.

Custom Post Type for Courses

Define the custom post type in inc/courses/cpt.php:

```php
function create_course_post_type() {
    register_post_type('course', [
        'labels' => [
            'name' => __('Courses'),
            'singular_name' => __('Course'),
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'courses'],
        'supports' => ['title', 'editor', 'thumbnail'],
    ]);
}
add_action('init', 'create_course_post_type');
```

### Course Listing Page

Create archive-course.php to display all courses.
Use a grid layout with course thumbnails, titles, and a "Learn More" link.

### single Course Page

Create single-course.php to display individual course details.
Include sections for the course description, syllabus, and instructor bio.

### Integration with WooCommerce

Set up WooCommerce and associate courses as purchasable products.
Override WooCommerce templates as needed in the woocommerce/ folder.

### Test Courses

Add sample courses and test the listing and detail pages.
Verify WooCommerce purchase flow.

## Phase 3: User Authentication

### Goal: Build user registration, login, and dashboard functionality.

#### 1- Custom Registration & Login

Add custom templates for registration and login (register.php, login.php).
Handle registration and login logic in inc/auth/register.php and inc/auth/login.php.

#### 2- User Redirects

Redirect users to their dashboard after login or registration.
Add the logic in inc/auth/redirect.php.
Dashboard Setup

Create dashboard.php with a welcome message and links to other sections.
Include sections for "My Courses," "Profile," and "Purchase History."

#### 3- Access Control

Ensure non-logged-in users are redirected to the login page for protected pages.

```php
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url());
    exit;
}
```

#### 4- Test Authentication

Verify the registration, login, and logout workflows.
Test dashboard accessibility for logged-in users.

## Phase 4: Course Interaction

### Goal: Allow users to interact with and track their purchased courses.

#### 1- Enroll Users in Courses

Save course enrollment data in user meta (enrollment.php).
Add functionality to display enrolled courses in the dashboard.

#### 2- Course Player

Create course-player.php for users to access course content.
Include video playback, downloadable resources, and a progress tracker.

#### 3- Progress Tracking

Implement progress tracking in progress.php.
Save completion data in the database.

#### 4- Test Course Interaction

Enroll a test user in a course and verify access to the player.
Check progress tracking and completion logic.

## Phase 5: Design & Styling

### Goal: Enhance the visual appeal and ensure a responsive design.

#### 1- Global Styles

Define global typography, colors, and layout in main.css.
Add responsiveness in responsive.css.

#### 2- Page-Specific Styles

Style key pages: dashboard.css for the dashboard, and course-player.css for the course player.

#### 3-Interactive Elements

Add hover effects for buttons and links.
Use JavaScript for interactive elements (e.g., collapsible sections in the course player).

#### 4- Test Design

Test the theme on various devices (desktop, tablet, mobile).
Ensure consistency across all pages.

## Phase 7: Deployment

### Goal: Prepare the theme for deployment and launch.

#### 1- Code Review & Optimization

Clean up code and remove unnecessary files.
Minify CSS and JS files.

#### 2-Testing

Perform end-to-end testing on a staging site.
Test on different browsers and devices.

#### 3- Documentation

Add a readme.txt file with theme setup instructions.
Document custom functions and templates for developers.

#### 4-Launch

Zip the theme folder and upload it to the WordPress admin panel.
Activate the theme and verify functionality on the live site.
