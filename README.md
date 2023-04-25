# Setup

- Run `setup.php` in `config/`
  - Execute this file by running `php setup.php` in command line
- This copies the `.env.example` to `.env`
- Fill in each field. Replace as necessary 
  - If you have not yet created a user that can access the database, do so first

# Notes

- `start-nginx.sh` is for Nate only. Don't worry about it.
- `views/` is the directory for all the php files
  - `_assets/` is for images
- PHP files that process stuff in `views/` should have an underscore at the beginning