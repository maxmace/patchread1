A horizontal toggle flip switch at the top to toggle between "responsive" look and "hardware" look

### The Ensoniq SQ-80 utilizes the NEC FIP80B5R Vacuum Fluorescent Display (VFD). 

1. Table of VFD chars and their ascii equivalents

2. lookup table of human readable substitutions (including tricky single character "dot numerals".

3. Get codebase to work with HP41 character set, truetype font 41CHRSET.TTF

      To use 41CHRSET.TTF as a webfont, convert the .ttf file to web-friendly formats (WOFF2/WOFF) using a tool like Transfonter or Font Squirrel. Place the files in your project, define them in your CSS using @font-face, and apply the font-family to your app. 

      #### Step-by-Step Implementation:
      1. Convert the Font:
          1. Go to Transfonter.
          2. Upload 41CHRSET.TTF.
          3. Ensure WOFF and WOFF2 are selected (best for modern browsers).
          4. Click "Convert" and download the zip file.
      2. Add to Project:
          1. Extract the contents.
          2. Move the generated .woff2 and .woff files into your project's assets folder (e.g., /fonts).
      3. Define in CSS (@font-face):
          1. In your main CSS file, add the following code, adjusting the URL to where you placed the files:
                ##### css
                ```
                @font-face {
                    font-family: 'MyCustomFont';
                    src: url('fonts/41CHRSET.woff2') format('woff2'),
                         url('fonts/41CHRSET.woff') format('woff');
                    font-weight: normal;
                    font-style: normal;
                }
                ```
      4. Use the Font:
          1. Apply the font to your SPA's CSS:
                ##### css
                 ```
                  body {
                      font-family: 'MyCustomFont', sans-serif;
                  }
                 ```
      
      ##### Tips for Single Page Apps (SPAs):
      * If using React/Vue/Angular, place font files in the public or assets folder.
      * If using Webpack, you may need to configure file-loader or url-loader to handle font files.
