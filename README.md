# Processmaker Package Crud
This package provides the necessary base code to start the developing a package in ProcessMaker 4.

## Postman Collection
Download the postman collection to see the endpoints: [PackageCRUD.postman_collection.json](PackageCRUD.postman_collection.json)

## Development
If you need to create a new ProcessMaker package run the following commands:

```
git clone https://github.com/ProcessMaker/package-crud.git
cd package-crud
php rename-project.php package-crud
composer install
npm install
npm run dev
```

## Installation
* Use `composer require processmaker/package-crud` to install the package.
* Use `php artisan package-crud:install` to install generate the dependencies.

## Navigation and testing
* Navigate to administration tab in your ProcessMaker 4
* Select `Skeleton Package` from the administrative sidebar

## Uninstall
* Use `php artisan package-crud:uninstall` to uninstall the package
* Use `composer remove processmaker/package-crud` to remove the package completely

## PHPUnit
 ![image](https://github.com/user-attachments/assets/598a7fd3-e483-4d84-9d3f-a09298dcc1d1)
