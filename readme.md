# Imdex
Imdex is an image index page, designed to work together with my screenshot tool, [Superscrot](https://github.com/horsedrowner/Superscrot). It displays a grid of images and directories as tiles. 

## Features
* Directory tiles display the newest image in that directory, and display the number of files in that directory;
* Lazy image loading (using [bLazy](https://github.com/dinbror/blazy/));

See it in action at <http://s.horsedrowner.net/>.

## Quick start
1. Have Ruby installed;
2. Clone this repository:

    ```
    $ git clone https://github.com/horsedrowner/Imdex.git
    ```
    
3. Create the configuration file `config.yml`, next to `config.ru`, with the following contents:

    ```yaml
    ---
      ## Relative or absolute path to the base directory containing the images. Examples:
      # basedir: /var/www/images/
      # basedir: D:\Images
      basedir: public 
    ```

4. Inside the cloned repository, run `bundle`;
5. Run the app using your favorite app server, or use `rackup`. Make sure the following environment variables are present:
    - `GITHUB_KEY`: GitHub Client ID;
    - `GITHUB_SECRET`: GitHub Client Secret;
    - `SESSION_SECRET`: Arbitrary string used for session cookies.

## Troubleshooting
### THIS FILENAME IS FUCKED UP
This (incredibly professional) warning message signals files whose filenames have an invalid encoding. Filenames are interpreted as UTF-8. If a file shows this warning message, it might help to run the following bash script. Many thanks to [geirha for this answer at Ask Ubuntu](http://askubuntu.com/a/113346).

```bash
#!/bin/bash

# list of encodings to try. (max 10)
enc=( latin1 windows-1252 )

while IFS= read -rd '' file <&3; do
    base=${file##*/} dir=${file%/*}

    # if converting from utf8 to utf8 succeeds, we'll assume the filename is ok.
    iconv -f utf8 <<< "$base" >/dev/null 2>&1 && continue

    # display the filename converted from each enc to utf8
    printf 'In %s:\n' "$dir/"
    for i in "${!enc[@]}"; do
        name=$(iconv -f "${enc[i]}" <<< "$base")
        printf '%2d - %-12s: %s\n' "$i" "${enc[i]}" "$name"
    done
    printf ' s - Skip\n'

    while true; do
        read -p "? " -n1 ans
        printf '\n'
        if [[ $ans = [0-9] && ${enc[ans]} ]]; then
            name=$(iconv -f "${enc[ans]}" <<< "$base")
            mv -iv "$file" "$dir/$name"
            break
        elif [[ $ans = [Ss] ]]; then
            break
        fi
    done
done 3< <(LC_ALL=C find . -depth -name "*[![:print:][:space:]]*" -print0)
```
