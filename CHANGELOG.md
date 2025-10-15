# Changelog

## 1.16.0 (WIP)

- Refactor `RouteBuilder`: improve documentation, simplify route creation, and rename methods for clarity.
- Remove unused commands in InstallCommand
- Add favicon and apple-touch-icon images
- Refactor `head` partial to remove redundant lines

Update .gitignore to include storage/debugbar; add apple-touch-icon.png and favicon.ico files; refactor RouteBuilder for improved clarity and structure

## 1.15.2
- Refactor Filterable trait: implement pagination reset logic when filters change=
- Fix pagination button sizes for better UI consistency

## 1.15.1
- Add `MoneyCast` for handling monetary
- refactor `DateRange` enum for improved clarity
- enhance `Filterable` trait with date range filtering logic

## 1.15.0
- remove unused elements from home page and enhance footer structure; 
- create a dedicated error partial for improved error handling in input components;
- update filterable trait methods for clarity in filter labeling and display values.

## 1.14.2
- Updated `setFormProperties` method in `WithFormActions` trait properties correctly.

## 1.14.1
- Refactor pagination views: remove old templates and implement a new livewire
  pagination component with improved accessibility and functionality
  
## 1.14.0
- Added `FloatAsInteger` cast to handle float values stored as integers in the database.
- Removed `MoneyCast` in favor of `FloatAsInteger` for better clarity and functionality.
- Update label component to optionally use `Str::headline` for improved title formatting.

## 1.13.0
- Refactor modal to include focus trap for better accessibility.
- Added `Filterable` trait to enhance Livewire components with dynamic filtering capabilities.
- Added `HasFormattedDates` trait to add query scopes and standardise date formatting across models.

## 1.12.0
- Standardise date format configuration and mappings for picker libraries to ensure accurate formatting across the app.
- Refactor form action trait to fully reset form state after saving or canceling, enhancing reliability in UI workflows.
- Update NPM dependencies and scripts for improved compatibility.

## 1.11.0
- Update `WithFormActions` trait to dispatch custom events for resource actions.
- Add `resetForm` method to fully reset form state.
- Refactor form property assignment to use PHP Reflection for type-safe handling.
- Update resource action button to allow custom event names.

## 1.10.5
- Update `Formable` trait to use PHP Reflection for type-safe property assignment.
- Ensure integer properties are cast correctly, preventing empty strings from being converted to zero.

## 1.10.4
- Improve `CodeRendererExtension` to accept block content or a file path for Mermaid diagrams.

## 1.10.3
- Enhance `CodeRendererExtension` so that `+parse-mermaid` supports both
  importing Mermaid diagrams from a file path and rendering inline Mermaid code
  blocks.

## 1.10.2
- Fix `checkbox` not correctly rendering in `v2` components
  
## 1.10.1
- Add watcher to `slim-select` component to fix issue with `slim-select` not updating when the model changes
  
## 1.10.0
- Remove `choices` component
- Add `slim-select` component

## 1.9.1
- Add `choices` component

## 1.9.0
- Update for Laravel 12 compatibility
- Refactor `composer.json` and `.env.example` for dependency updates;
- remove `postcss.config.js` and enhance InstallCommand with NPM scripts management.

## 1.8.1
- Add support for Torchlight PHP parsing; ensure proper wrapping in `<pre>` tags for improved rendering.

## 1.8.0
- refactor form components to improve error messaging for missing `for` or `wire:model` attributes
- refactor form components to use v2 structure

## 1.7.3
- Add loading indicator to filepond
- Change prefix on the search component

## 1.7.2
- Updated `nk-ckeditor.js` to include `hr`
- Refactor saveWithUnique method in FileManagementService to use 'path' instead of 'directory' for clarity

## 1.7.1
- Refactor file upload handling in Crudable trait to improve storage configuration management

## 1.7.0
- Changed `selectedItemId` to `selectedId` and updated necessary components
- Updated `cancel` method to reset forms and dispatch `close-modal` event

## 1.6.0
- Update button variation components

## 1.5.1
- Add torchlight support for code highlighting 
- Removed highlight.js and related code in markdown layout
- Refactor environment and vite configuration 
- improve layout components
- update PublishedStatus enum colors

## 1.5.0
- Updated button component 
- Updated resource-action component 
- Added PublishedStatus Enum

## 1.4.0
- Refactor CKEditor components for v2
- Removed deprecated CKEditor components and their associated partials.

## 1.3.1
- Refactor help text with dedicated component
- Refactor menu components: introduce `menu-item` and `menu-link` components for improved structure and flexibility
- Add `Editor` component and ckeditor controls.

## 1.3.0
- Refactor datepicker component: add icon support and improve control group structure; 
- Add reset public properties after saving to `WithFormActions`

## 1.2.3
- Enhance `CodeRendererExtension` to automatically add `Mermaid` component to render mermaid diagrams
  
## 1.2.2
- add `$for` attribute to control groups to correctly associate labels and errors with inputs

## 1.2.1
- Fix `email` and `password` components with incorrect component name

## 1.2.0
- Renamed `control-input` to `control-with-addons` and updated form components for more intuitive naming
- Refactor input components to improve structure and error handling, add form-row partial
- Breaking change: Renamed `WithLivewireHelpers` to `WithFormActions` for more intuitive naming
- Breaking change: Renamed `naykel.php` config file to `gotime.php`

## 1.1.0
- Update label component to use headline method for improved title formatting
- Update pikaday date format to align the the `Date` cast format

## 1.0.8
- Removed `bx-content` class from `Box` component `slot` attribute
- Added `Filterable` trait

## 1.0.7
- Added `Box` component

## 1.0.6
- Add CSRF token meta tag and improve title case handling in table header component

## 1.0.5
- Add exception handling for missing createNewModel method in WithLivewireHelpers trait

## 1.0.4
- Refactor spacing and formatting for consistency
- Enhance Renderable trait by adding dynamic view resolution and improving render method
- Format `th` using `Str::headline` for improved readability

## 1.0.3
- Fix academic icons
- Removed `icon` class from button and added `wh-1.5`
  
## 1.0.2
- Added `calcPercentage` math helper function
- Add custom debug mode configuration to `naykel.php` config file
- Add more icons
  
## 1.0.1
- Update filepond component to avoid control layout for error display 
- Added dispatch pondReset event in Crudable trait

## 1.0.0
- Initial v1 release
