<?php
# Copyright (c) 2015, phpfmt and its authors
# All rights reserved.
#
# Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:
#
# 1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
#
# 2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
#
# 3. Neither the name of the copyright holder nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.
#
# THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

if (ini_get('phar.readonly')) {
	unset($argv[0]);
	$ret = 0;
	passthru(PHP_BINARY . ' -dphar.readonly=0 ' . __FILE__ . ' ' . implode(' ', $argv) . ' 2>&1', $ret);
	exit($ret);
}
require __DIR__ . '/../vendor/dericofilho/csp/csp.php';
require __DIR__ . '/../src/Core/constants.php';
require __DIR__ . '/../src/Core/FormatterPass.php';
require __DIR__ . '/../src/Additionals/AdditionalPass.php';
require __DIR__ . '/../src/Additionals/EncapsulateNamespaces.php';
require __DIR__ . '/../src/version.php';
require __DIR__ . '/../src/helpers.php';

error_reporting(E_ALL);
$opt = getopt('Mmp');
$newver = '';
$verPattern = '<?php define(\'VERSION\', \'%d.%d.%d\');';
$tmp = explode('.', VERSION);
if (isset($opt['M'])) {
	$newver = sprintf($verPattern, $tmp[0] + 1, 0, 0);
} elseif (isset($opt['m'])) {
	$newver = sprintf($verPattern, $tmp[0], $tmp[1] + 1, 0);
} elseif (isset($opt['p'])) {
	$newver = sprintf($verPattern, $tmp[0], $tmp[1], $tmp[2] + 1);
}
if (!empty($newver)) {
	echo 'Bumping version to: ', $newver, PHP_EOL;
	file_put_contents(__DIR__ . '/../src/version.php', $newver);
}

class Build extends FormatterPass {
	public function candidate($source, $foundTokens) {
		return true;
	}

	public function format($source) {
		$this->tkns = token_get_all($source);
		$this->code = '';
		$curlyStack = [];
		while (list($index, $token) = eachArray($this->tkns)) {
			list($id, $text) = $this->getToken($token);
			$this->ptr = $index;
			switch ($id) {
				case T_NAMESPACE:
					if ($this->rightUsefulTokenIs(T_STRING)) {
						list($rId, $rText) = $this->rightUsefulToken();
						if ('Extern' == $rText) {
							$this->walkUntil(ST_CURLY_OPEN);
							$curlyStack[] = T_NAMESPACE;
							continue;
						}
					}
					$this->appendCode($text);
					break;

				case ST_CURLY_OPEN:
				case T_CURLY_OPEN:
				case T_DOLLAR_OPEN_CURLY_BRACES:
					$curlyStack[] = $id;
					$this->appendCode($text);
					break;

				case ST_CURLY_CLOSE;
					$foundId = array_pop($curlyStack);
					if (T_NAMESPACE == $foundId) {
						continue;
					}
					$this->appendCode($text);
					break;

				case T_REQUIRE:
					list($id, $text) = $this->walkUntil(T_CONSTANT_ENCAPSED_STRING);
					$fn = str_replace(['"', "'"], '', $text);
					$fn = str_replace(['"', "'"], '', $fn);

					if (is_file(__DIR__ . '/../src/' . $fn)) {
						$fn = __DIR__ . '/../src/' . $fn;
					} elseif (is_file(__DIR__ . '/' . $fn)) {
						$fn = __DIR__ . '/' . $fn;
					}

					$source = file_get_contents($fn);
					$source = (new EncapsulateNamespaces())->format($source);
					$included = token_get_all($source);
					if (T_OPEN_TAG == $included[0][0]) {
						unset($included[0]);
					}
					while (list(, $token) = each($included)) {
						list($id, $text) = $this->getToken($token);
						if (T_COMMENT == $id || T_DOC_COMMENT == $id) {
							continue;
						}
						if (T_REQUIRE == $token || T_REQUIRE_ONCE == $token || T_INCLUDE == $token || T_INCLUDE_ONCE == $token) {
							fwrite(STDERR, 'found ' . $text . '. Include files must not have include files.');
							exit(-1);
						}
						$this->appendCode($text);
					}
					$this->walkUntil(ST_SEMI_COLON);
					break;
				default:
					$this->appendCode($text);
			}
		}
		return $this->code;
	}
}

$pass = new Build();

$chn = make_channel();
$chn_done = make_channel();
$workers = 2;
echo 'Starting ', $workers, ' workers...', PHP_EOL;
for ($i = 0; $i < $workers; ++$i) {
	cofunc(function ($pass, $chn, $chn_done) {
		while (true) {
			$target = $chn->out();
			if (empty($target)) {
				break;
			}
			echo $target, PHP_EOL;
			file_put_contents(__DIR__ . '/' . $target . '.php', $pass->format(file_get_contents(__DIR__ . '/../src/' . $target . '.src.php')));
			if (file_exists(__DIR__ . '/../src/' . $target . '.stub.src.php')) {
				file_put_contents(__DIR__ . '/' . $target . '.stub.php', $pass->format(file_get_contents(__DIR__ . '/../src/' . $target . '.stub.src.php')));
			}
		}
		$chn_done->in('done');
	}, $pass, $chn, $chn_done);
}

$targets = ['fmt', 'fmt-external', 'refactor'];
foreach ($targets as $target) {
	$chn->in($target);
}

for ($i = 0; $i < $workers; ++$i) {
	$chn->in(null);
}
for ($i = 0; $i < $workers; ++$i) {
	$chn_done->out();
}
$chn->close();
$chn_done->close();

echo 'Building PHARs...';
$phars = ['fmt', 'fmt-external', 'refactor'];
foreach ($phars as $target) {
	file_put_contents(__DIR__ . '/' . $target . '.stub.php', '<?php namespace {$inPhar = true;} ' . preg_replace('/' . preg_quote('<?php') . '/', '', file_get_contents(__DIR__ . '/' . $target . '.stub.php'), 1));
	$phar = new Phar(__DIR__ . '/' . $target . '.phar', FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $target . '.phar');
	$phar[$target . '.stub.php'] = file_get_contents(__DIR__ . '/' . $target . '.stub.php');
	$phar->setStub('#!/usr/bin/env php' . "\n" . $phar->createDefaultStub($target . '.stub.php'));
	file_put_contents(__DIR__ . '/' . $target . '.phar.sha1', sha1_file(__DIR__ . '/' . $target . '.phar'));
	unlink(__DIR__ . '/' . $target . '.stub.php');
}
echo 'done', PHP_EOL;

$variants = ['.php' => 0755, '.phar' => 0755, '.phar.sha1' => 0444];
foreach ($targets as $target) {
	foreach ($variants as $variant => $permission) {
		if (file_exists(__DIR__ . '/' . $target . $variant)) {
			echo 'moving ', $target . $variant, ' to bin' . DIRECTORY_SEPARATOR . $target . $variant, PHP_EOL;
			rename(__DIR__ . '/' . $target . $variant, __DIR__ . '/../bin/' . $target . $variant);
			chmod(__DIR__ . '/../bin/' . $target . $variant, $permission);
		}
	}
}

$readmePath = __DIR__ . '/../README.md';
$readmeContent = file_get_contents($readmePath);

$cmd = PHP_BINARY . ' ' . __DIR__ . '/../bin/fmt.phar --list-simple';
$passes = explode(PHP_EOL, trim(`$cmd`));
$passes = implode(PHP_EOL,
	array_map(function ($v) {
		return ' * ' . $v;
	}, $passes)
);

$cmd = 'cd ' . __DIR__ . '/../bin && ' . PHP_BINARY . ' fmt.phar --help';
$help = trim(`$cmd`);

$readmeContent = preg_replace_callback('/<!-- help START -->(.*)<!-- help END -->/s', function ($matches) use ($help) {
	return '<!-- help START -->' . PHP_EOL . '```bash
$ php fmt.phar --help
' . $help . '
```' . PHP_EOL . '<!-- help END -->';
}, $readmeContent);

$readmeContent = preg_replace_callback('/<!-- transformations START -->(.*)<!-- transformations END -->/s', function ($matches) use ($passes) {
	return '<!-- transformations START -->' . PHP_EOL . $passes . PHP_EOL . '<!-- transformations END -->';
}, $readmeContent);

file_put_contents($readmePath, $readmeContent);
