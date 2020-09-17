const Path = require('path');
const fs = require('fs');
const rimraf = require("rimraf");
const crypto = require('crypto');

const faviconPath = Path.resolve(__dirname, 'build/images/favicon/favicon.svg');
const iconDir = Path.resolve(__dirname, 'web/icons');
const headTemplateDir = Path.resolve(__dirname, 'web/modules/custom/head/templates');

let faviconBuffer = fs.readFileSync(faviconPath);
let sum = crypto.createHash('md5');
sum.update(faviconBuffer);
const hex = sum.digest('hex');

const hexDir = iconDir + '/' + hex;

rimraf.sync(iconDir);
fs.mkdirSync(hexDir, { recursive: true });

var favicons = require('favicons'),
    source = faviconPath,
    configuration = {
        path: '/icons/' + hex,
        appName: 'Brand Rehab',
        appShortName: 'Brand Rehab',
        appDescription: 'Brand Rehab application',
        developerName: 'Wayne Ashley <wayne@brand.rehab>',
        developerURL: 'https://brand.rehab',
        dir: "auto",
        lang: "en-US",
        background: "#fff",
        theme_color: "#fff",
        appleStatusBarStyle: "black-translucent",
        display: "standalone",
        orientation: "portrait",
        scope: "/",
        start_url: "/",
        version: "1.0",
        logging: true,
        pixel_art: false,
        loadManifestWithCredentials: false,
        icons: {
            android: true,
            appleIcon: true,
            appleStartup: true,
            coast: true,
            favicons: true,
            firefox: true,
            windows: true,
            yandex: true
        }
    },
    callback = function (error, response) {
        if (error) {
            console.log(error.message);
            return;
        }

        response.files.forEach(function(file) {
          fs.writeFileSync(hexDir + '/' + file['name'], file['contents']);
        });

        response.images.forEach(function(image) {
          fs.writeFileSync(hexDir + '/' + image['name'], image['contents']);
        });

        fs.writeFileSync(headTemplateDir + '/icons.html.twig', response.html.join("\n"));
    };

  favicons(source, configuration, callback);
