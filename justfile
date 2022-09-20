svn_dir := "~/svn"
repo_name := "mash-monetize-earn-and-grow-your-experiences-w-bitcoin-lightning"

@help:
  just --list --justfile {{justfile()}}

# copy code over to svn repository
@commit:
  cp ./readme.txt {{svn_dir}}/{{repo_name}}/trunk
  cp ./mash.php {{svn_dir}}/{{repo_name}}/trunk
  cp ./uninstall.php {{svn_dir}}/{{repo_name}}/trunk
  cp -r ./assets/ {{svn_dir}}/{{repo_name}}/assets
  cp -r ./js/ {{svn_dir}}/{{repo_name}}/trunk/js
  cp -r ./includes/ {{svn_dir}}/{{repo_name}}/trunk/includes
  cp -r ./images/ {{svn_dir}}/{{repo_name}}/trunk/images
  cp -r ./css/ {{svn_dir}}/{{repo_name}}/trunk/css
  cp -r ./shortcodes/ ${{svn_dir}}/{{repo_name}}/trunk/shortcodes
  cp -r ./build/ ${{svn_dir}}/{{repo_name}}/trunk/build
  
version := ""
# build plugin zip file and create release
@build:
  mkdir ./releases/v{{version}}
  zip -R ./releases/v{{version}}/mash-wordpress-plugin-{{version}}.zip "assets/*" "css/*" "images/*" "includes/*" "js/*" "shortcodes/*" "build/*" "LICENSE" "readme.txt" "mash.php" "uninstall.php"



