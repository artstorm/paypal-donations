# PowerShell Build Script for PayPal Donations
#
# @author       Johan Steen
# @date         18 Feb 2013
# @version      1.0


# ------------------------------------------------------------------------------
# Variables
# ------------------------------------------------------------------------------

# Generic
$VERSION = '1.0'
$DATE    = get-date -format "d MMM yyyy"
$FILES   = @('paypal-donations.php', 'readme.txt')

# ------------------------------------------------------------------------------
# Build
# Replaces Version and Date in the plugin. 
# ------------------------------------------------------------------------------
function build_plugin
{
    Write-Host '--------------------------------------------'
    Write-Host 'Building plugin...'
    # cd $LESS_FOLDER

    # Replace Date and Version
    foreach ($file in $FILES)
    {
        cat $file `
            | %{$_ -replace "@BUILD_DATE", $DATE} `
            | %{$_ -replace "@DEV_HEAD", $VERSION} `
            | Set-Content $file'.tmp' 

        cp $file'.tmp' $file
        Remove-Item $file'.tmp'
    }
    Write-Host "Plugin successfully built! - $DATE"
}

$VERSION = Read-Host 'New version number'
build_plugin
