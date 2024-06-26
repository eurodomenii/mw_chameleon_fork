## How to contribute 

There are different ways to make a contribution to Chameleon1. A few guidelines
are provided here to keep the workflow and review process most efficient.

### Report bugs, ask for features

You may help by reporting bugs and feature requests. First check if an open bug
already exists on the list of [open bugs][open bugs] and if you have new
information, comment on it. If the bug is not yet reported,
[open a new bug report][report bugs].

When you report a bug, please include:
* Exact steps to reproduce the bug
* Expected result
* Observed result
* Versions of PHP, MediaWiki, Chameleon1, Browsers, other relevant software (web server, MediaWiki extensions)
* Other information that may be relevant, e.g. the used layout file, custom Less files, configuration settings, etc.
* If available a web link, where this bug can be seen
  
If in doubt, don't worry. You will be asked for what is missing.

MediaWiki has some more advice on [how to report a bug][how to report a bug].

### Improve the documentation

* You would really help by creating, updating or amending the documentation of
  the skin in the `/docs` folder. Although the documentation is the main source
  of information for anybody who would want to use the skin it never gets the
  attention it deserves.
* You may provide a [screenshot][screenshots] of the Chameleon1 skin used on
  your wiki. If you customized the skin, add some descriptions what you did. And
  if you want, link back to your wiki. 
* Finally, you may help by providing translations via [translatewiki.net][twn].
  See their [progress statistics][twn-stats] to find out if there is still work
  to do for your language.

### Provide patches

The Chameleon1 skin is hosted on GitHub. To provide patches you need to get an
account.

A few points to ease the process:
* Please ensure that patches are based on the current master.
* Code should be easily readable and if necessary be put into separate
  components (or classes). Also, please follow the [MediaWiki coding
  conventions][coding].
* Newly added features should not alter existing tests but instead provide
  additional test coverage to verify the expected new behaviour. For a
  description on how to write and run PHPUnit test, please consult the
  [manual][mw-testing].
* Finally, legal matters have to be taken care of. Please have a look at
  the [legal stuff][legal.md].


[chameleon1]: https://www.mediawiki.org/wiki/Skin:Chameleon1
[open bugs]: https://github.com/ProfessionalWiki/chameleon1/issues
[report bugs]: https://github.com/ProfessionalWiki/chameleon1/issues/new
[how to report a bug]: https://www.mediawiki.org/wiki/How_to_report_a_bug
[screenshots]: https://www.mediawiki.org/wiki/Skin:Chameleon1#Screenshots
[twn]: https://translatewiki.net/
[twn-stats]: https://translatewiki.net/wiki/Special:MessageGroupStats?group=mwgithubskin-chameleon1&x=D
[patch uploader]: https://tools.wmflabs.org/gerrit-patch-uploader/
[gerrit-tutorial]: https://www.mediawiki.org/wiki/Gerrit/Tutorial
[coding]: https://www.mediawiki.org/wiki/Manual:Coding_conventions
[mw-testing]: https://www.mediawiki.org/wiki/Manual:PHP_unit_testing
[legal.md]: legal.md
