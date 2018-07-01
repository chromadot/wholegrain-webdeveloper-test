### Frontend/Backend tests

The "backend" directory contains:-
- The theme directory: wholegrain_theme. The theme creates a recipe custom post type with the specified custom meta. The theme also sets up the AJAX function responsible for delivering the recipes.
- The jquery_recipe_consume_code directory contains a jQuery snippet for consuming the recipes via AJAX call. Currently, the code makes the request to a subdomain I've set up with the theme activated, which already has 3 test recipe posts. The ajaxurl variable on line 6 contains the url which can be changed to another domain if needed.

The "frontend" directory is the HTML/CSS version of the PDF mockup.

### Questions

**Tell us one thing you find most websites are doing poorly? How would you fix it?**

Optimization. Still seeing far too many images oversized ( both in scale and file size ) and absurd CSS/JS file sizes. I think it's mostly a result of Wordpress websites commonly using 30+ plugins. Some of the popular large plugins have huge CSS/JS files. My solution is to use custom code wherever possible, that only has features specific to the clients website needs, as opposed to it working in every context possible - which usually means bloated code. As far as images go, taking the time to scale images to a suitable size and compress them well.

**One of our staff is facing a bug on the website you cannot reproduce. How would you proceed?**

Firstly, if possible, I would try to get in front of their PC, or access it via a remote desktop. Then, assuming I'm familiar with the code, I would add a broad layer of extra logging to point me to the correct general section of code. From there, a more in-depth layer of logging in that section to isolate the exact issue. If I can't find any issue, and the bug is only reproduceable on a single PC, then I would assume something specific to the PC setup is the cause.
