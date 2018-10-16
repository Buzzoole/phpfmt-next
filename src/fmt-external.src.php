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

namespace Extern {
	require __DIR__ . '/../vendor/symfony/console/Formatter/OutputFormatterInterface.php';
	require __DIR__ . '/../vendor/symfony/console/Helper/HelperInterface.php';
	require __DIR__ . '/../vendor/symfony/console/Helper/Helper.php';
	require __DIR__ . '/../vendor/symfony/console/Formatter/OutputFormatterStyleStack.php';
	require __DIR__ . '/../vendor/symfony/console/Formatter/OutputFormatterStyleInterface.php';
	require __DIR__ . '/../vendor/symfony/console/Formatter/OutputFormatterStyle.php';
	require __DIR__ . '/../vendor/symfony/console/Formatter/OutputFormatter.php';
	require __DIR__ . '/../vendor/symfony/console/Output/OutputInterface.php';
	require __DIR__ . '/../vendor/symfony/console/Output/ConsoleOutputInterface.php';
	require __DIR__ . '/../vendor/symfony/console/Output/Output.php';
	require __DIR__ . '/../vendor/symfony/console/Output/StreamOutput.php';
	require __DIR__ . '/../vendor/symfony/console/Output/ConsoleOutput.php';
	require __DIR__ . '/../vendor/symfony/console/Helper/ProgressBar.php';
}

namespace {
	$concurrent = function_exists('pcntl_fork');
	if ($concurrent) {
		require __DIR__ . '/../vendor/dericofilho/csp/csp.php';
	}
	require __DIR__ . '/Core/Cacher.php';
	$enableCache = false;
	if (class_exists('SQLite3')) {
		$enableCache = true;
		require __DIR__ . '/Core/Cache.php';
	} else {
		require __DIR__ . '/Core/Cache_dummy.php';
	}

	require __DIR__ . '/version.php';
	require __DIR__ . '/helpers.php';
	require __DIR__ . '/selfupdate.php';

	require __DIR__ . '/Core/constants.php';
	require __DIR__ . '/Core/FormatterPass.php';
	require __DIR__ . '/Additionals/AdditionalPass.php';
	require __DIR__ . '/Core/BaseCodeFormatter.php';

	require __DIR__ . '/Core/SandboxedPass.php';
	require __DIR__ . '/Core/SingleCodeFormatter.php';

	if (!isset($inPhar)) {
		$inPhar = false;
	}
	if (!isset($testEnv)) {
		require __DIR__ . '/cli-external.php';
	}
}