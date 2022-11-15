# 🎬 NetVOD

This repository contains the code of our NetVOD app, built by:

- Maxence PETIT
- Gaspard BAUBY
- Jules HOLDER

## ❓ What exactly is NetVOD?

NetVOD is a video streaming platform, where you can watch series, post reviews, organize your viewing list with an automatic sorting of ongoing and completed series, as well as a bookmarking system allowing you to easily find your favorite series.

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
| [PHP](https://www.php.net/) | 8.0.25 (Webetu restriction) | We use PHP for the backend of our application and also the frontend (at least HTML is generated via PHP). It manages our authentication system, our database connection and the different pages of our application |
| [HTML](https://developer.mozilla.org/en-US/docs/Web/HTML) | 5 | We use HTML to structure the pages of our application, as it is required for a web application. We tried to follow a pretty logical structure in terms of semantics so that our website is accessible to everyone. |
| [CSS](https://developer.mozilla.org/en-US/docs/Web/CSS) | 3 | We used CSS to style our pages. We thought about using some utility-driven CSS framework, like [TailwindCSS](https://tailwindcss.com/), but we decided to stick to a more traditional approach since Tailwind felt a bit overkill. |
| [MariaDB](https://mariadb.org/) | 10.5 | We use MariaDB as our database, since it is the database used for Webetu. It is a fork of MySQL, which is a relational database management system. We use it to store our users' data, as well as the episodes and series they watch, their bookmarks, etc. |
| [Apache](https://httpd.apache.org/) | 2.4.6 (Webetu restriction) | We use Apache as our web server, since it is the web server used for Webetu. When deploying our app on Webetu, we configured our .htaccess file to only allow requests to our index.php file (which is the entry point of our application) and the assets directory which contains series |

## Tasks

| Task| Author | Comment | Type 
|--|--|--|--|
| [Task #1](https://github.com/MaxenceIUT/NetVOD/issues/1) | Maxence PETIT | Connection with login and password to acces to the platform. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #2](https://github.com/MaxenceIUT/NetVOD/issues/2)| Maxence PETIT| Register with a mail and double password. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #3](https://github.com/MaxenceIUT/NetVOD/issues/3)| Gaspard BAUBY| Displays all the catalogue | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #4](https://github.com/MaxenceIUT/NetVOD/issues/4)| Jules HOLDER| Acces to series details with the list of episodes. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green) 
| [Task #5](https://github.com/MaxenceIUT/NetVOD/issues/5)| Gaspard BAUBY | Displays an selected episode from a serie | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #6](https://github.com/MaxenceIUT/NetVOD/issues/6)| Jules HOLDER | Possible to add a serie to bookmarked list from the current user. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green) 
| [Task #7](https://github.com/MaxenceIUT/NetVOD/issues/7)| Jules HOLDER| Shows the bookmarked series from the current user | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #8](https://github.com/MaxenceIUT/NetVOD/issues/8)| Gaspard BAUBY | Displays in home website ongoing series. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #9](https://github.com/MaxenceIUT/NetVOD/issues/9)| Gaspard BAUBY| Allow users to post review series | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #10](https://github.com/MaxenceIUT/NetVOD/issues/10)| Maxence PETIT | Displays average note and all reviews of one series. | ![Base functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-base-green)
| [Task #11](https://github.com/MaxenceIUT/NetVOD/issues/11)| Maxence PETIT| Account activation link | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #12](https://github.com/MaxenceIUT/NetVOD/issues/12)| - |  Lack of time: Possibility to research a serie in the catalog with a search bar. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #13](https://github.com/MaxenceIUT/NetVOD/issues/13)| Jules HOLDER| Sort the catalogue by title, upload date, number of episodes, with the possibility to invert the list | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #14](https://github.com/MaxenceIUT/NetVOD/issues/14)| Gaspard BAUBY| Possibility to filter the series catalog by genre or by public age. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #15](https://github.com/MaxenceIUT/NetVOD/issues/15)| Jules HOLDER | Remove series from the user's bookmark | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #16](https://github.com/MaxenceIUT/NetVOD/issues/16)| Gaspard BAUBY| Display in home website the series already seen by the user. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #17](https://github.com/MaxenceIUT/NetVOD/issues/17)| Jules HOLDER | Customize user's profile with name, surname, favorite genre | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #18](https://github.com/MaxenceIUT/NetVOD/issues/18)| Gaspard BAUBY| Direct acces in on going series to the upcomming after the last seen. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #19](https://github.com/MaxenceIUT/NetVOD/issues/19)| Jules HOLDER| Sort the catalogue by average rating | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)
| [Task #20](https://github.com/MaxenceIUT/NetVOD/issues/20)| Gaspard BAUBY| A service to reset password with a token. | ![Extended functionnality](https://img.shields.io/badge/%E2%9A%A1%20feat-extended-blue)

## ❓ How to launch our application

### To login to our website, use the followings indications :

Email : `test@test.com`

Password: `password`

