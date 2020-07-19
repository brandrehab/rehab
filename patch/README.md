# Working With Git Patches

## Create a patch
From the contrib module directory create a repo.
```
git init .
git add .
git commit -m "initial commit"
git checkout -b fix
```
Make any changes you want, commit them and generate the patch file.
```
git add .
git commit -m "implementing the fix"
git format-patch master
```
Move the generated patch file to the root patches directory underneath the name of the contrib module.  
With this done, you should checkout the master version of the module, then delete the .git file.

## Apply a patch
From the root of the install.
```
git apply -p1 --directory=web/modules/contrib/some_module patch/some_module/some_fix.patch
```

## Revert a patch
From the root of the install.
```
git apply -R -p1 --directory=web/modules/contrib/some_module patch/some_module/some_fix.patch
```
