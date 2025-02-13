# Changelog

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
