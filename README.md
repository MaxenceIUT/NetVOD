# 🎬 NetVOD

This repository contains the code of our NetVOD app, built by:

- Maxence PETIT
- Gaspard BAUBY
- Jules HOLDER

## ❓ What exactly is NetVOD?

NetVOD is a **video streaming platform** (SVOD), where you can watch series, post reviews, organize your viewing list with an automatic sorting of ongoing and completed series, as well as a bookmarking system allowing you to easily find your favorite series.

## 📝 Main features

- 🔐 Secured account creation and login
- 🖌️ Account personalization (first name, last name, favorite genre)
- 🔎 Series search through our catalog
- ♻️ Automatic sorting of ongoing and completed series
- ⭐ Public review system
- 🔖 Bookmarking system

## 👨‍💻 Tech stack

Here's a summary of the tech stack used in this project:
| Name | Version | Description |
|------|---------|-------------|
| [PHP](https://www.php.net/) | 8.0.25 (Webetu restriction) | PHP is used for the backend of our application. It generates the HTML based on the page the user is on. |
| [HTML](https://developer.mozilla.org/en-US/docs/Web/HTML) | 5 | HTML is the standard markup language for creating web pages. The HTML structure of our application follows semantic rules, to ensure a good SEO and accessibility. |
| [CSS](https://developer.mozilla.org/en-US/docs/Web/CSS) | 3 | CSS is the standard choice when it comes to styling a web page. We thought about using some utility-driven CSS framework, like [TailwindCSS](https://tailwindcss.com/), but we decided to stick to a more traditional approach since Tailwind felt a bit overkill for the size of our website. |
| [MariaDB](https://mariadb.org/) | 5.5.68 | MariaDB is the database used on Webetu which is why we had to stick with it. It stores our users' data, as well as the episodes and series they watch, their bookmarks, etc. |
| [Apache](https://httpd.apache.org/) | 2.4.6 (Webetu restriction) | Apache is the web server used on Webetu. When deploying our app on Webetu, we configured our .htaccess file to only allow requests to our index.php file (which is the entry point of our application) and the assets directory which contains series. That way, we can't access any other file on the server such as the database credentials or the source code. |

## ❓ How to access our application

### If you want to test our website, and don't want to register yet, you can use the following credentials to access a test account:

Email : `test@test.com`

Password: `password`

## 🏭 How to deploy our application

For a full guide on how to deploy our application, please refer to the [HOSTING.md](HOSTING.md) file.

## Tasks

| Task| Author | Comment | Type 
|--|--|--|--|
| [Task #1](https://github.com/MaxenceIUT/NetVOD/issues/1) | Maxence PETIT | Allows the user to login using an email and a password, granting access to the platform if the credentials are valid | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #2](https://github.com/MaxenceIUT/NetVOD/issues/2)| Maxence PETIT| Allows the user to register to NetVOD by providing their first and last name, their email, and a password | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #3](https://github.com/MaxenceIUT/NetVOD/issues/3)| Gaspard BAUBY| Display a list of all the series available on the platform | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #4](https://github.com/MaxenceIUT/NetVOD/issues/4)| Jules HOLDER| Detailed view of a series and its episodes | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green) 
| [Task #5](https://github.com/MaxenceIUT/NetVOD/issues/5)| Gaspard BAUBY | Detailed view of an episode (video player) | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #6](https://github.com/MaxenceIUT/NetVOD/issues/6)| Jules HOLDER | Allows the user to add a series to their bookmarks | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green) 
| [Task #7](https://github.com/MaxenceIUT/NetVOD/issues/7)| Jules HOLDER| Display the list of all series the user added to its bookmarks | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #8](https://github.com/MaxenceIUT/NetVOD/issues/8)| Gaspard BAUBY | Automatically update a list of ongoing series when one of its episodes is watched | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #9](https://github.com/MaxenceIUT/NetVOD/issues/9)| Gaspard BAUBY| Allow the user to post a review for a series | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #10](https://github.com/MaxenceIUT/NetVOD/issues/10)| Maxence PETIT | Display the series rating on its page and allow an user to read all the posted reviews | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #11](https://github.com/MaxenceIUT/NetVOD/issues/11)| Maxence PETIT | The user must activate his account after registration | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #12](https://github.com/MaxenceIUT/NetVOD/issues/12)| - |  Lack of time: Add a way to search for a series in the catalog using a full text search bar | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #13](https://github.com/MaxenceIUT/NetVOD/issues/13)| Jules HOLDER | Sort series from the catalog by title, upload date, number of episodes, ascending and descending orders | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #14](https://github.com/MaxenceIUT/NetVOD/issues/14)| Gaspard BAUBY | Filter series from the catalog by genre or by public age. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #15](https://github.com/MaxenceIUT/NetVOD/issues/15)| Jules HOLDER | Allows the user to remove a series from their bookmarks | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #16](https://github.com/MaxenceIUT/NetVOD/issues/16)| Gaspard BAUBY| Display the list of all series the user finished, based on the episodes they've seen | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #17](https://github.com/MaxenceIUT/NetVOD/issues/17)| Jules HOLDER | Allows the user to edit their first and last name, as well as set a favorite genre | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #18](https://github.com/MaxenceIUT/NetVOD/issues/18)| Gaspard BAUBY | Directly link to the next episode the user needs to watch for a series when displayed in its ongoing series list. If the user has seen the fourth episode of a series, they'll be able to automatically access to the fifth episode by clicking on the series card in their account page | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #19](https://github.com/MaxenceIUT/NetVOD/issues/19)| Jules HOLDER | Sort series from the catalog by average rating | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #20](https://github.com/MaxenceIUT/NetVOD/issues/20)| Gaspard BAUBY | Add a forgot password button to the login page so the user can recover their account if they forgot their password, using a randomly generated token | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)