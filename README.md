# Setup

- Run `setup.php` in `config/`
  - Execute this file by running `php setup.php` in command line
- This copies the `.env.example` to `.env`
- Fill in each field. Replace as necessary 
  - If you have not yet created a user that can access the database, do so first
- Serve the website

# Notes

- `_assets/` is for images
- The files in `scripts/` are executed for forms and the like
- Access the admin panel through `/admin.php`
  - Default Email: `admin@site.com`
  - Default Password: `admin`
  - It is recommended to change the details of the admin account
- Header and Footer are in `lib/HtmlComponent.php`
  - Must execute Header after any error checks and redirects
  - Then execute Footer at the end, after any JS
  - Do this for every page. See `index.php` for a sample


# Todo

- [x] Login for member
- [x] Register for member
- [x] Login for seller
- [x] Register for seller
- [x] Create product
- [x] Update product
- [x] Read product
- [x] Delete product
- [ ] Buy stuff
- [x] Update profile details
  - [x] Profile Picture
- [ ] Create reviews
- [ ] View reviews
- [ ] Delete reviews
- [ ] Update reviews
- [ ] Unified error messages and behavior
- [x] Search products
  - [ ] Search categories?

# Features

- [ ] Wishlist
- [ ] Notifications
- [ ] Admin / Report
- [ ] Cart / Voucher