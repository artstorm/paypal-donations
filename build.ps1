# Build Script for WordPress Plugins
#
# @author       Johan Steen <artstorm at gmail dot com>
# @uri          http://johansteen.se/
# @date         4 Apr 2013

# ------------------------------------------------------------------------------
# Variables and Setup
# ------------------------------------------------------------------------------

# Make the script culture independent (ie, don't give me Swedish month names!)
$ct                  = [System.Threading.Thread]::CurrentThread
$ic                  = [System.Globalization.CultureInfo]::InvariantCulture
$ct.CurrentCulture   = $ic
$ct.CurrentUICulture = $ic

# Generic
$PLUGIN_NAME  = 'PayPal Donations'
$DATE         = get-date -format "d MMM yyyy"
$FILES        = @('paypal-donations.php', 'readme.txt')
$PLUGIN_FILE  = 'paypal-donations.php'
$POT_FILE     = 'lang/paypal-donations.pot'
$SVN_REPO     = 'http://plugins.svn.wordpress.org/paypal-donations/'

# ------------------------------------------------------------------------------
# Bump
# Prepares strings for a new release. 
# ------------------------------------------------------------------------------
function bump($newVersion)
{
    $oldVersion = findVersionNumber

    Write-Host $('-' * 80)
    Write-Host "BUMP" -foregroundcolor "White"
    Write-Host "Bumping $oldVersion to $newVersion" -noNewLine
    # Let's have some dots printed out. Makes it old skool
    for ($ctr = 0; $ctr -lt 3; $ctr++) {
        Write-Host "." -noNewLine
        sleep -Milliseconds 500
    }
    Write-Host "."

    findReplaceFile $PLUGIN_FILE "Version: $oldVersion" "Version: $newVersion"
    bumpMessage $PLUGIN_FILE": bumped Version $oldVersion to $newVersion"
    findReplaceFile 'readme.txt' "Stable tag: $oldVersion" "Stable tag: $newVersion"
    bumpMessage "readme.txt: bumped Stable Tag $oldVersion to $newVersion"
    # For now, I keep the master branch readme pointing to develop...
    # So I don't forget to change it back after a release. Revise if I come up
    # with a better method to handle this during a release.
    # findReplaceFile 'README.md' "\?branch=develop" "?branch=master"
    # bumpMessage "README.md: Changed Travis CI badge from develop to master branch"
    git add .
    git commit -m "Bumps version number."

    bumpMessage "pot file: Updating..."
    xgettext -o  $POT_FILE -L php --keyword=_e --keyword=__  `
    *.php views/*.php lib/PayPalDonations/*.php

    git add .
    git commit -m "Updates pot file."

    Write-Host "Done!"
    Write-Host $('-' * 80)

    Write-Host "Changes since v$oldVersion"
    git log $oldVersion`..HEAD --oneline
    Write-Host $('-' * 80)
}

function findReplaceFile($file, $old, $new)
{
    cat $file `
        | %{$_ -replace $old, $new} `
        | Set-Content "$($file).tmp"

    correctEncoding("$($file).tmp")

    # Copy and clean up
    cp "$($file).tmp" $file
    Remove-Item "$($file).tmp"
}

function bumpMessage($message)
{
    Write-Host "- $message" -foregroundcolor "DarkGray"
    sleep -Milliseconds 250
}

function correctEncoding($file)
{
    # Set UNIX line endings and UTF-8 encoding.
    Get-ChildItem $file | ForEach-Object {
        # get the contents and replace line breaks by U+000A
        $contents = [IO.File]::ReadAllText($_) -replace "`r`n?", "`n"
        # create UTF-8 encoding without signature
        $utf8 = New-Object System.Text.UTF8Encoding $false
        # write the text back
        [IO.File]::WriteAllText($_, $contents, $utf8)
    }
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

    # Trim away white space
    $version = $version.trim()

    return $version
}

# ------------------------------------------------------------------------------
# SVN
# Push a new release to the WordPress Repository
# ------------------------------------------------------------------------------
function svn
{
    $version = findVersionNumber

    Write-Host "Version to build: $version"

    # Checkout Update trunk in repo
    Write-Host "Checks out trunk..."
    svn.exe co $SVN_REPO"trunk/" build/trunk
    Write-Host "Removes old version..."
    svn.exe rm build/trunk/*
    Write-Host "Copyies new version to trunk..."
    cp $PLUGIN_FILE build/trunk/
    cp readme.txt build/trunk/

    cp assets/  -Destination build/trunk/assets/  -Recurse
    cp lang/    -Destination build/trunk/lang/    -Recurse
    cp lib/     -Destination build/trunk/lib/     -Recurse
    cp views/   -Destination build/trunk/views/   -Recurse

    Write-Host "Commits trunk to repo..."
    cd build/trunk
    svn.exe add *
    svn.exe ci -m "Updates trunk with version $version"

    if (!$LastExitCode -eq 0) {
        Write-Host "Error! Could not update trunk. Exiting." -foregroundcolor "Red"
        Exit
    }

    # Tag it
    Write-Host "Tagging the new version"
    svn.exe cp -m "Tagged version $version" $SVN_REPO"trunk/" $SVN_REPO"tags/$version"

    if (!$LastExitCode -eq 0) {
        Write-Host "Error! Could not create the new tag. Exiting." -foregroundcolor "Red"
        Exit
    }

    # Cleanup
    cd ../..
    Remove-Item build -Recurse -Force

    # Git tag the new version, and push master to the repo.
    git tag -a $version -m "Tagged version $version"
    git push origin master --tags

    Write-Host "All done!"
}

# ------------------------------------------------------------------------------
# Assets
# Update the assets in the WordPress Repository
# ------------------------------------------------------------------------------
function assets
{
    Write-Host "Checking out assets folder..."
    svn.exe co $SVN_REPO"assets/" build
    if (!$LastExitCode -eq 0) {
        Write-Host "Error! Could not checkout the assets. Exiting." -foregroundcolor "Red"
        Exit
    }

    Write-Host "Updating screenshots..."
    Remove-Item build/*.*
    Copy-Item repo/screenshot-*.* build/
    Copy-Item repo/banner-*.png build/

    Write-Host "Commiting the assets folder..."
    svn.exe add --force build/*.jpg
    svn.exe add --force build/*.png
    cd build
    svn.exe ci -m "Updates repository assets."
    if (!$LastExitCode -eq 0) {
        Write-Host "Error! Could not commit the assets. Exiting." -foregroundcolor "Red"
        Exit
    }

    cd ..
    Remove-Item build -Recurse -Force
    Write-Host "All done!"
}

# ------------------------------------------------------------------------------
# Console Output
# ------------------------------------------------------------------------------
function header
{
    Write-Host $('-' * 80)
    Write-Host $PLUGIN_NAME -foregroundcolor "White"
    Write-Host "Version: $(findVersionNumber)"
    Write-Host $('-' * 80)
}

function checklist
{
    Write-Host "CHECKLIST"  -foregroundcolor "Red"
    Write-Host "Before tagging the new release"
    Write-Host "* Update changelog." -foregroundcolor "White"
    Write-Host "* Run unit tests." -foregroundcolor "White"
    Write-Host $('-' * 80)
}

function arguments
{
    Write-Host "ARGUMENTS"  -foregroundcolor "White"
    Write-Host "bump     Bumps the version number of the plugin."
    Write-Host "svn      Push a new release to the WordPress repository."
    Write-Host "assets   Updates the assets in the WordPress repository."
    Write-Host $('-' * 80)
}

# ------------------------------------------------------------------------------
# Check Environment
# ------------------------------------------------------------------------------

<##
 # Checks if a function or cmdlet exists.
 # If the command does not exist, display an error message and exit.
 #
 # @param  $cmdName  function or cmdlet to check.
 # @param  $solMess  Solution message to display.
 # @return void
 #>
function commandExists($cmdName, $solMess)
{
    if (!(Get-Command $cmdName -errorAction SilentlyContinue))
    {
        "Error: $cmdName does not exists!"
        "Solution: $errMess"
        Exit
    } 
}

## Start checking the environment
commandExists 'Get-GitStatus' 'Get posh-git for PowerShell.'

# ------------------------------------------------------------------------------
# Handle Arguments
# ------------------------------------------------------------------------------

switch ($args[0])
{
    "bump" {
        header

        # Check branch
        $gitStatus = Get-GitStatus('.')
        if (!$gitStatus.Branch.StartsWith('release'))
        {
            Write-Host "Only bump in release branches..." -foregroundcolor "Red"
            Exit
        }

        # Set new version number
        $newVersion = Read-Host 'New version number'
        if ($newVersion -eq '') {
            Write-Host "Exited..." -foregroundcolor "Red"
            break
        }

        # And do the bumps
        bump($newVersion)

        # Let's display some reminders
        checklist
        break
    }

    "svn" {
        header

        # Check branch
        $gitStatus = Get-GitStatus('.')
        if (!$gitStatus.Branch.StartsWith('master')) {
            Write-Host "Only publish releases from the master branch..." `
                -foregroundcolor "Red"
            Exit
        }
        svn
    }


    "assets" {
        header
        assets
    }

    default {
        header
        arguments
    }
}
