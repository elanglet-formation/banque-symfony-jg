php bin/console cache:clear
mkdir .\ic\docker\image-apache\app\
robocopy . .\ic\docker\image-apache\app\  /NFL /NDL /NJH /NJS /nc /ns /np /MIR /XF .project .buildpath .gitignore /XD .settings\ .git\ ic\
