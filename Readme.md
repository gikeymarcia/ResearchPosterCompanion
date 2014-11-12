# PosterCompanion
An easy to edit tool to make an accompanying website for your poster presentations.

#### How do I use this?
This companion is meant to run on a webserver that has PHP installed.  If you have a server like that then all you are going to do is open up the `settings.php` file and edit some values.  If you're already a user of [Collector](http://www.github.com/gikeymarcia/Collector) then the same server that runs your studies will work with this tool.

### Editing `settings.php`
#### `$title`
This will be the text displayed in the tab on the viewer's internet browser.  Edit it as you wish.
#### `$posterImgName`
This is the name of the file that contains the imgage of your poster.  Typically I've found that an image of about 1800 pixels wide works well.  The website is setup to scale the image to the size of the browser window so having a large image makes it possible for people to make the browser full screen and see a bigger version of your poster.  Try to make the image size less than 1MB or else the page might load slowly (especially on mobile).
Place your image in the main folder. To make your job easier just name your poster `poster.jpg` then copy in and overwrite the included file.
#### `$posterFile`
Filename of the downloadable copy of the poster.  The filename is case sensitive so the download will only work if the file is named exactly as you put in this variable. Usually the best format to download is a `.pdf` becasue it opens correctly on just about any device imaginable.
Place your pdf in the main folder.
#### `$contactAuthors`
Here is where you put the names and contact information of the authors of the poster.  The names/emails for each author are input as a single item in an array.
PHP arrays follow the format of

````PHP
$array = array( 'item', 'another item', 'etc.');
````
For this program each item is formatted as `'Author Name::Author email'`.  If there are multiple authors remember that when inputting multiple items into an array each item should be proceeded by a comma, `,`.
#### `$demoExpFolders`
If your experiments are all in Collector then you can copy the experiment folders into the main folder and let people experience what participants saw.  Just like in `$contactAuthors`, each experiment is entered as an item in an array.  The format of the item is `FolderName\`. Try to avoid using spaces in your folder names because some server configurations might freak out if there are spaces. Better safe than sorry.
#### `$creditCollector`
Simple, this is either `TRUE` or `FALSE`.  If you set as true it will add some text beneath the contact the authors section that promotes the use of Collector.  As always, I think Collector is great and the more people it can help the better.

Enjoy,
[Mikey](http://www.github.com/gikeymarcia)