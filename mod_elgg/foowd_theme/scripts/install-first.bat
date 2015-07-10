npm install
bower install
handlebars pages/templates/*.handlebars -f pages/templates/templates-amd.js --amd
stylus -u jeet -u rupture lib/css/styl/*.styl -o lib/css/partials/
grunt
