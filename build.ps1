# PowerShell Build Script for PayPal Donations
#
# @author       Johan Steen
# @date         5 Mar 2013
# @version      1.1


# ------------------------------------------------------------------------------
# Variables and Setup
# ------------------------------------------------------------------------------

# Make the script culture independent (ie, don't give me Swedish month names!)
$currentThread = [System.Threading.Thread]::CurrentThread
$culture       = [System.Globalization.CultureInfo]::InvariantCulture
$currentThread.CurrentCulture   = $culture
$currentThread.CurrentUICulture = $culture

# Generic
$VERSION     = '1.0'
$DATE        = get-date -format "d MMM yyyy"
$FILES       = @('paypal-donations.php', 'readme.txt')
$PLUGIN_FILE = 'paypal-donations.php'

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

        # Set UNIX line endings and UTF-8 encoding.
        Get-ChildItem $file'.tmp' | ForEach-Object {
          # get the contents and replace line breaks by U+000A
          $contents = [IO.File]::ReadAllText($_) -replace "`r`n?", "`n"
          # create UTF-8 encoding without signature
          $utf8 = New-Object System.Text.UTF8Encoding $false
          # write the text back
          [IO.File]::WriteAllText($_, $contents, $utf8)
        }

        cp $file'.tmp' $file
        Remove-Item $file'.tmp'
    }
    Write-Host "Plugin successfully built! - $DATE"
}

function findVersionNumber
{
  # The file comes in as an array (one line per key)
  $plugin = cat $PLUGIN_FILE
  # Convert it to string, with new lines added
  $plugin = [string]::Join("`n", ($plugin))

  # Search the plugin for the current version number
  $regex = [regex]"(?<=Version:)[^`n]*"
  $version =  $regex.Match($plugin).Value

  # Trim away white space, and convert from string to decimal
  $version = [decimal] $version.trim()

  return $version
}

function header
{
    Write-Host $('-' * 80)
    Write-Host 'PayPal Donations'
    Write-Host 'Version: ' -NoNewLine
    findVersionNumber
    Write-Host $('-' * 80)
}

function checklist
{
    Write-Host "Checklist"  -foregroundcolor "Red"
    Write-Host $('-' * 80)
    Write-Host "Before tagging the new release"
    Write-Host "* Update .pot file." -foregroundcolor "White"
    Write-Host "* Update changelog." -foregroundcolor "White"
    Write-Host "* Run unit tests." -foregroundcolor "White"
    Write-Host $('-' * 80)
}


# ------------------------------------------------------------------------------
# Handle Arguments
# ------------------------------------------------------------------------------

switch ($args[0])
{
    "bump" {
        $newVersion = Read-Host 'New version number'
        checklist
        break
    }

    default {
        header
    }
}
