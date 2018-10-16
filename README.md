# phpfmt

[![Build Status](https://travis-ci.org/phpfmt-next/fmt.svg?branch=master)](https://travis-ci.org/phpfmt-next/fmt)

## Requirements

- PHP >= 5.6.0 to run the formatter. Note that the formatter can parse even a PHP file version 4 in case needed. HHVM is not supported.

## Usage

```bash
$ php fmt.phar filename.php
```

```bash
$ php fmt.phar --help
Usage: fmt.phar [-hv] [-o=FILENAME] [--config=FILENAME] [--cache[=FILENAME]] [options] <target>
  --cache[=FILENAME]                cache file. Default: .php.tools.cache
  --cakephp                         Apply CakePHP coding style
  --config=FILENAME                 configuration file. Default: .phpfmt.ini
  --constructor=type                analyse classes for attributes and generate constructor - camel, snake, golang
  --dry-run                         Runs the formatter without atually changing files; returns exit code 1 if changes would have been applied
  --enable_auto_align               disable auto align of ST_EQUAL and T_DOUBLE_ARROW
  --exclude=pass1,passN,...         disable specific passes
  --help-pass                       show specific information for one pass
  --ignore=PATTERN-1,PATTERN-N,...  ignore file names whose names contain any PATTERN-N
  --indent_with_space=SIZE          use spaces instead of tabs for indentation. Default 4
  --lint-before                     lint files before pretty printing (PHP must be declared in %PATH%/$PATH)
  --list                            list possible transformations
  --list-simple                     list possible transformations - greppable
  --no-backup                       no backup file (original.php~)
  --passes=pass1,passN,...          call specific compiler pass
  --profile=NAME                    use one of profiles present in configuration file
  --psr                             activate PSR1 and PSR2 styles
  --psr1                            activate PSR1 style
  --psr1-naming                     activate PSR1 style - Section 3 and 4.3 - Class and method names case.
  --psr2                            activate PSR2 style
  --selfupdate                      self-update fmt.phar from Github
  --setters_and_getters=type        analyse classes for attributes and generate setters and getters - camel, snake, golang
  --smart_linebreak_after_curly     convert multistatement blocks into multiline blocks
  --version                         version
  --visibility_order                fixes visibiliy order for method in classes - PSR-2 4.2
  --yoda                            yoda-style comparisons
  -h, --help                        this help message
  -o=-                              output the formatted code to standard output
  -o=file                           output the formatted code to "file"
  -v                                verbose

If <target> is "-", it reads from stdin
```

## Supported Transformations

- AddMissingParentheses             Add extra parentheses in new instantiations.
- AliasToMaster                     Replace function aliases to their masters - only basic syntax alias.
- AlignConstVisibilityEquals        Vertically align "=" of visibility and const blocks.
- AlignDoubleArrow                  Vertically align T_DOUBLE_ARROW (=>).
- AlignDoubleSlashComments          Vertically align "//" comments.
- AlignEquals                       Vertically align "=".
- AlignGroupDoubleArrow             Vertically align T_DOUBLE_ARROW (=>) by line groups.
- AlignPHPCode                      Align PHP code within HTML block.
- AlignTypehint                     Vertically align function type hints.
- AllmanStyleBraces                 Transform all curly braces into Allman-style.
- AutoPreincrement                  Automatically convert postincrement to preincrement.
- AutoSemicolon                     Add semicolons in statements ends.
- CakePHPStyle                      Applies CakePHP Coding Style
- ClassToSelf                       "self" is preferred within class, trait or interface.
- ClassToStatic                     "static" is preferred within class, trait or interface.
- ConvertOpenTagWithEcho            Convert from "<?=" to "<?php echo ".
- DocBlockToComment                 Replace docblocks with regular comments when used in non structural elements.
- DoubleToSingleQuote               Convert from double to single quotes.
- EchoToPrint                       Convert from T_ECHO to print.
- EncapsulateNamespaces             Encapsulate namespaces with curly braces
- GeneratePHPDoc                    Automatically generates PHPDoc blocks
- IndentTernaryConditions           Applies indentation to ternary conditions.
- JoinToImplode                     Replace implode() alias (join() -> implode()).
- LeftWordWrap                      Word wrap at 80 columns - left justify.
- LongArray                         Convert short to long arrays.
- MergeElseIf                       Merge if with else.
- SplitElseIf                       Merge if with else.
- MergeNamespaceWithOpenTag         Ensure there is no more than one linebreak before namespace
- MildAutoPreincrement              Automatically convert postincrement to preincrement. (Deprecated pass. Use AutoPreincrement instead).
- NewLineBeforeReturn               Add an empty line before T_RETURN.
- OrganizeClass                     Organize class, interface and trait structure.
- OrderAndRemoveUseClauses          Order use block and remove unused imports.
- OnlyOrderUseClauses               Order use block - do not remove unused imports.
- OrderMethod                       Organize class, interface and trait structure.
- OrderMethodAndVisibility          Organize class, interface and trait structure.
- PHPDocTypesToFunctionTypehint     Read variable types from PHPDoc blocks and add them in function signatures.
- PrettyPrintDocBlocks              Prettify Doc Blocks
- PSR2EmptyFunction                 Merges in the same line of function header the body of empty functions.
- PSR2MultilineFunctionParams       Break function parameters into multiple lines.
- ReindentAndAlignObjOps            Align object operators.
- ReindentSwitchBlocks              Reindent one level deeper the content of switch blocks.
- RemoveIncludeParentheses          Remove parentheses from include declarations.
- RemoveSemicolonAfterCurly         Remove semicolon after closing curly brace.
- RemoveUseLeadingSlash             Remove leading slash in T_USE imports.
- ReplaceBooleanAndOr               Convert from "and"/"or" to "&&"/"||". Danger! This pass leads to behavior change.
- ReplaceIsNull                     Replace is_null($a) with null === $a.
- RestoreComments                   Revert any formatting of comments content.
- ReturnNull                        Simplify empty returns.
- ShortArray                        Convert old array into new array. (array() -> [])
- SmartLnAfterCurlyOpen             Add line break when implicit curly block is added.
- SortUseNameSpace                  Organize use clauses by length and alphabetic order.
- SpaceAroundControlStructures      Add space around control structures.
- SpaceAfterExclamationMark         Add space after exclamation mark.
- SpaceAroundExclamationMark        Add spaces around exclamation mark.
- SpaceAroundParentheses            Add spaces inside parentheses.
- SpaceBetweenMethods               Put space between methods.
- StrictBehavior                    Activate strict option in array_search, base64_decode, in_array, array_keys, mb_detect_encoding. Danger! This pass leads to behavior change.
- StrictComparison                  All comparisons are converted to strict. Danger! This pass leads to behavior change.
- StripExtraCommaInArray            Remove trailing commas within array blocks
- StripNewlineAfterClassOpen        Strip empty lines after class opening curly brace.
- StripNewlineAfterCurlyOpen        Strip empty lines after opening curly brace.
- StripNewlineWithinClassBody       Strip empty lines after class opening curly brace.
- StripSpaces                       Remove all empty spaces
- StripSpaceWithinControlStructures Strip empty lines within control structures.
- TightConcat                       Ensure string concatenation does not have spaces, except when close to numbers.
- TrimSpaceBeforeSemicolon          Remove empty lines before semi-colon.
- UpgradeToPreg                     Upgrade ereg_* calls to preg_*
- WordWrap                          Word wrap at 80 columns.
- WrongConstructorName              Update old constructor names into new ones. http://php.net/manual/en/language.oop5.decon.php
- YodaComparisons                   Execute Yoda Comparisons.

## What does the Code Formatter do?

### K&R configuration

<table>
<tr>
<td>Before</td>
<td>After</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
for($i = 0; $i &lt; 10; $i++)
{
if($i%2==0)
echo "Flipflop";
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
for ($i = 0; $i &lt; 10; $i++) {
	if ($i%2 == 0) {
		echo "Flipflop";
	}
}
</code></pre>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
$a = 10;
$otherVar = 20;
$third = 30;
</code></pre>
</td>
<td>
<pre><code>&lt;?php
$a        = 10;
$otherVar = 20;
$third    = 30;
</code></pre>
<i>This can be disabled with the option "disable_auto_align"</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
namespace NS\Something;
use \OtherNS\C;
use \OtherNS\B;
use \OtherNS\A;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
</td>
<td>
<pre><code>&lt;?php
namespace NS\Something;

use \OtherNS\A;
use \OtherNS\C;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
<i>note how it sorts the use clauses, and removes unused ones</i>
</td>
</tr>
</table>

### PSR configuration

<table>
<tr>
<td>Before</td>
<td>After</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
for($i = 0; $i &lt; 10; $i++)
{
if($i%2==0)
echo "Flipflop";
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
for ($i = 0; $i &lt; 10; $i++) {
    if ($i%2 == 0) {
        echo "Flipflop";
    }
}
</code></pre>
<i>Note the identation of 4 spaces.</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
class A {
function a(){
return 10;
}
}
</code></pre>
</td>
<td>
<pre><code>&lt;?php
class A
{
    public function a()
    {
        return 10;
    }
}
</code></pre>
<i>Note the braces position, and the visibility adjustment in the method a().</i>
</td>
</tr>
<tr>
<td>
<pre><code>&lt;?php
namespace NS\Something;
use \OtherNS\C;
use \OtherNS\B;
use \OtherNS\A;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
</td>
<td>
<pre><code>&lt;?php
namespace NS\Something;

use \OtherNS\A;
use \OtherNS\C;
use \OtherNS\D;

$a = new A();
$b = new C();
$d = new D();
</code></pre>
<i>note how it sorts the use clauses, and removes unused ones</i>
</td>
</tr>
</table>
