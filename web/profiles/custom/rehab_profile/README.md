Rehab distribution profile.  

When preparing config/install:  
1. Delete core.extension.yml, file.settings.yml and update.settings.yml
2. ```find /path/to/PROFILE_NAME/config/install/ -type f -exec sed -i '' '/^uuid: /d' {} \;```
3. ```find /path/to/PROFILE_NAME/config/install/ -type f -exec sed -i '' '/_core:/{N;d;}' {} \;```
