svn_dir := "~/svn"
repo_name := "mash-monetize-earn-and-grow-your-experiences-w-bitcoin-lightning"

@help:
  just --list --justfile {{justfile()}}

# copy code over to svn repository
@commit:
  cp ./readme.txt {{svn_dir}}/{{repo_name}}/trunk
  cp ./mash.php {{svn_dir}}/{{repo_name}}/trunk
  cp ./uninstall.php {{svn_dir}}/{{repo_name}}/trunk
  cp -R ./assets/ {{svn_dir}}/{{repo_name}}/assets
  cp -R ./js/ {{svn_dir}}/{{repo_name}}/trunk/js
  cp -R ./includes/ {{svn_dir}}/{{repo_name}}/trunk/includes
  cp -R ./images/ {{svn_dir}}/{{repo_name}}/trunk/images
  cp -R ./css/ {{svn_dir}}/{{repo_name}}/trunk/css
  cp -R ./build/ {{svn_dir}}/{{repo_name}}/trunk/build
  cp -R ./shortcodes/ {{svn_dir}}/{{repo_name}}/trunk/shortcodes
  
  
version := ""
# build plugin zip file and create release
@build:
  yarn build
  mkdir ./releases/v{{version}}
  zip -R ./releases/v{{version}}/mash-wordpress-plugin-{{version}}.zip "assets/*" "css/*" "images/*" "includes/*" "js/*" "shortcodes/*" "build/**/*" "LICENSE" "readme.txt" "mash.php" "uninstall.php" -x "node_modules/*"



