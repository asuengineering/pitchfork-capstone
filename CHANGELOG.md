# Change Log

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

### Version 0.5

- Added options for `project-sponsor` block to adjust display in other use cases.
- Created new layout for `taxonomy-sponsor.php` template.

### Version 0.4

- Adjustments for mobile display.
- Make `research-tag` taxonomy public to include in query loop block.

### Version 0.3

- Adjusted display od `research-theme` and `project-sponsor` blocks to display a warning to users in the block editor when taxonomy items or independent choices are not selected (null).
- Added unpublished `research-tag` taxonomy to help provide additional classifications of projects. Will help to facilitate query loops for specific subsets of projects.

### Version 0.2

Marks the beta version of the child theme deployed to a production environment.

- Initial deployment contains custom post type for `capstone` projects and supporting taxonomy collections.
- Created `capstone-hero` block as independent header for capstone projects.
- Created two supporting blocks for capstone project display + other flexible content arrangements: `research-theme` and `project-sponsor` blocks will display related taxonomy items or a user selected term depending on context.
