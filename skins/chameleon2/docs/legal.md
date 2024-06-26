## Legal considerations for contributors

The Chameleon2 skin is currently licensed under the GNU General Public License,
version 3 (or any later version). Its documentation is currently licensed under
GNU Free Documentation License, version 1.3 (or any later version). Any
contribution must be made under these same license.

However, it may in the future become necessary or desirable to change these
licenses, e.g. to keep this skin legally compatible with a changed license of
the MediaWiki software or to better position it in a changed legal context.

For this reason every contributor should provide the following statement with
their first commit on GitHub:
```
I understand and agree that the maintainer of the Chameleon2 skin shall have the
irrevocable and perpetual right to make and distribute copies of any
contribution, as well as to create and distribute collective works of any
contribution, under the current license or under any other open source license
chosen by the maintainer.
```

The current maintainer of the Chameleon2 skin is Stephan Gambke. He may appoint
another maintainer in the future.

### WTF!?

I discussed the above text with various people. In the course of this discussion
several questions (and answers) came up that are worth being recorded here.

#### Why would you want to change the license?

I do not have any specific reason in mind right now, but the above paragraph
gives two examples for possible situations where changing the license could be
useful: Compatibility with other software and license modernization.

Some cases I can think of:
* Right now the preferred installation method is to use Composer to install
  Chameleon2 and all its dependencies. This way I can maintain, that the skin
  is not distributed with the packages it depends on, and thus does not need to
  take care of their licenses. However, should I want to provide a tarball with
  the skin and all its dependencies I would have to more carefully check license
  compatibility.
* Chameleon2 itself might become part of a tarball, e.g. some pre-build,
  pre-configured wiki for I don't know what purpose.
* Parts of the skin might be included in other software. 
* If I get it right, it might be possible to use this skin (or a derivative)
  for other frameworks than MediaWiki. Didn't look into that, but it's
  conceivable.
* I might want to include some code from elsewhere that requires a license
  change. Although admittedly the GPL is at the more restrictive end of the
  scale, so including software with less restrictive licenses is usually not a
  problem.
* Well, and finally there may be some shiny new GPL4 in the future, that
  protects against whatever new scheme the big, bad industry has come up with.
  For an example of such a situation see the article [Why Upgrade to
  GPLv3][why-upgrade] by Richard Stallman.

#### And what would you maybe change it to?

Any open source license, that allows to maintain the Chameleon2 skin in a
sensible way while still retaining as much of the spirit of the original license
as possible. I certainly do not want to cheat contributors out of being
recognized for their work, so while I like its radical simplicity I'd probably
not go for [WTFPL][WTFPL].

#### Don't you need to get signatures or something similar?

The written signature is indeed a critical point. Many organizations (Python,
GNU, Mozilla, Apache) actually ask for that. But I certainly do not want to
involve myself in a lot of paperwork. So I try to get around that by asking to
add the statement to the commit message. Sure, it is possible to change the
history of a git repo, but doing so over all publicly available (and private)
copies of the repo (including the one on GitHub) should be not that easy.

#### Isn't a MediaWiki skin (by its very nature) derivative from MediaWiki and thus infected by its license anyway?

I do not think that skins and extensions are derivatives of MediaWiki. They do
not fork and change. Instead they are pluggable libraries that may or may not be
used with an MW installation. You could say they provide you with the means to
create a derivative, where - if you were to actually distribute it - you would
have to make sure all the licenses are compatible. So you might argue, that
anybody providing a package of MediaWiki and some skins/extensions, creates a
derivate. The MediaWiki tarball comes to mind.

From a practical point of view, if skins and extensions actually were
derivatives, it would be pointless to specify a separate license for them. And
MediaWiki could never incorporate any library that does not have by chance the
same license. Following that reasoning, you could even argue that all the
software on a computer needs to be compatible with the OS license.

#### Why not just wait until you come to the point where you want to change the license and ask people then?

Two answers. First, it might just not be possible to get hold of all the people.
Second, if anybody then disagrees, their contributions might have become an
integral part of the software such that removing them would not be realistically
feasible. And even if it were feasible it may be hard to remove their
contributions and replace them with something having the same functionality. The
new code will inevitably be similar to the old one and it might be hard to prove
that the one was not derived from the other.


[why-upgrade]: https://www.gnu.org/licenses/rms-why-gplv3.html
[wmf-git-server]: https://git.wikimedia.org/
[patch-uploader]: https://tools.wmflabs.org/gerrit-patch-uploader/
[WTFPL]: http://www.wtfpl.net
