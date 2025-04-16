# Deploying

This project is using Laravel 12 so for full compatibility please consider using PHP >= 8. This project is saving data in JSON file located in "storage\app\public\products.json".

Below is instruction how to deploy this app in two conditions.
1. PC that already installed PHP & Laravel.
2. PC that doesn't have Laravel yet or fresh installation.

# Already setup Laravel
1. Clone or extract project to any folder.
2. Open the project folder.
3. Open cmd or terminal.
4. Go to the extracted folder in cmd or terminal.
5. Run "php artisan serve".
6. Open "localhost:8000" in Browser.

# No Laravel yet (Windows)
1. Download and install Xampp or Lampp newest version that's using PHP 8 here https://www.apachefriends.org
2. After that, open "xampp-control.exe" file, usually it's in "C:\xampp\xampp-control.exe".
	- Click "Start" button in Apache row to start Apache service.
		- If there's some error, please try to browse it because the reason may be different for each person. Don't hesitate to contact me!
3. Extract "task-list.zip" to any folder in "C:\xampp\htdocs". THIS IS IMPORTANT! For example we will use "laravel-skill-test" as folder name.
4. Open the extracted folder. For example the targetted folder will be "C:\xampp\htdocs\laravel-skill-test".
5. Open "localhost/{extracted_folder_name/public}" in Browser. Using the provided example before, it will be "localhost/laravel-skill-test/public".
