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
	if ('1' === getenv('FMTDEBUG') || 'step' === getenv('FMTDEBUG')) {
		require __DIR__ . '/Core/CodeFormatter_debug.php';
	} elseif ('profile' === getenv('FMTDEBUG')) {
		require __DIR__ . '/Core/CodeFormatter_profile.php';
	} else {
		require __DIR__ . '/Core/CodeFormatter.php';
	}

	require __DIR__ . '/Core/AddMissingCurlyBraces.php';
	require __DIR__ . '/Core/AutoImport.php';
	require __DIR__ . '/Core/ConstructorPass.php';
	require __DIR__ . '/Core/EliminateDuplicatedEmptyLines.php';
	require __DIR__ . '/Core/ExternalPass.php';
	require __DIR__ . '/Core/ExtraCommaInArray.php';
	require __DIR__ . '/Core/LeftAlignComment.php';
	require __DIR__ . '/Core/MergeCurlyCloseAndDoWhile.php';
	require __DIR__ . '/Core/MergeDoubleArrowAndArray.php';
	require __DIR__ . '/Core/MergeParenCloseWithCurlyOpen.php';
	require __DIR__ . '/Core/NormalizeIsNotEquals.php';
	require __DIR__ . '/Core/NormalizeLnAndLtrimLines.php';
	require __DIR__ . '/Core/Reindent.php';
	require __DIR__ . '/Core/ReindentColonBlocks.php';
	require __DIR__ . '/Core/ReindentComments.php';
	require __DIR__ . '/Core/ReindentEqual.php';
	require __DIR__ . '/Core/ReindentObjOps.php';
	require __DIR__ . '/Core/ResizeSpaces.php';
	require __DIR__ . '/Core/RTrim.php';
	require __DIR__ . '/Core/SettersAndGettersPass.php';
	require __DIR__ . '/Core/SplitCurlyCloseAndTokens.php';
	require __DIR__ . '/Core/StripExtraCommaInList.php';
	require __DIR__ . '/Core/SurrogateToken.php';
	require __DIR__ . '/Core/TwoCommandsInSameLine.php';

	require __DIR__ . '/PSR/PSR1BOMMark.php';
	require __DIR__ . '/PSR/PSR1ClassConstants.php';
	require __DIR__ . '/PSR/PSR1ClassNames.php';
	require __DIR__ . '/PSR/PSR1MethodNames.php';
	require __DIR__ . '/PSR/PSR1OpenTags.php';
	require __DIR__ . '/PSR/PSR2AlignObjOp.php';
	require __DIR__ . '/PSR/PSR2CurlyOpenNextLine.php';
	require __DIR__ . '/PSR/PSR2IndentWithSpace.php';
	require __DIR__ . '/PSR/PSR2KeywordsLowerCase.php';
	require __DIR__ . '/PSR/PSR2LnAfterNamespace.php';
	require __DIR__ . '/PSR/PSR2ModifierVisibilityStaticOrder.php';
	require __DIR__ . '/PSR/PSR2SingleEmptyLineAndStripClosingTag.php';
	require __DIR__ . '/PSR/PsrDecorator.php';

	require __DIR__ . '/Additionals/AddMissingParentheses.php';
	require __DIR__ . '/Additionals/AliasToMaster.php';
	require __DIR__ . '/Additionals/AlignConstVisibilityEquals.php';
	require __DIR__ . '/Additionals/AlignDoubleArrow.php';
	require __DIR__ . '/Additionals/AlignDoubleSlashComments.php';
	require __DIR__ . '/Additionals/AlignEquals.php';
	require __DIR__ . '/Additionals/AlignGroupDoubleArrow.php';
	require __DIR__ . '/Additionals/AlignPHPCode.php';
	require __DIR__ . '/Additionals/AlignTypehint.php';
	require __DIR__ . '/Additionals/AllmanStyleBraces.php';
	require __DIR__ . '/Additionals/AutoPreincrement.php';
	require __DIR__ . '/Additionals/AutoSemicolon.php';
	require __DIR__ . '/Additionals/CakePHPStyle.php';
	require __DIR__ . '/Additionals/ClassToSelf.php';
	require __DIR__ . '/Additionals/ClassToStatic.php';
	require __DIR__ . '/Additionals/ConvertOpenTagWithEcho.php';
	require __DIR__ . '/Additionals/DocBlockToComment.php';
	require __DIR__ . '/Additionals/DoubleToSingleQuote.php';
	require __DIR__ . '/Additionals/EchoToPrint.php';
	require __DIR__ . '/Additionals/EncapsulateNamespaces.php';
	require __DIR__ . '/Additionals/GeneratePHPDoc.php';
	require __DIR__ . '/Additionals/IndentTernaryConditions.php';
	require __DIR__ . '/Additionals/JoinToImplode.php';
	require __DIR__ . '/Additionals/LeftWordWrap.php';
	require __DIR__ . '/Additionals/LongArray.php';
	require __DIR__ . '/Additionals/MergeElseIf.php';
	require __DIR__ . '/Additionals/SplitElseIf.php';
	require __DIR__ . '/Additionals/MergeNamespaceWithOpenTag.php';
	require __DIR__ . '/Additionals/MildAutoPreincrement.php';
	require __DIR__ . '/Additionals/NewLineBeforeReturn.php';
	require __DIR__ . '/Additionals/NoSpaceAfterPHPDocBlocks.php';
	require __DIR__ . '/Additionals/OrganizeClass.php';
	require __DIR__ . '/Additionals/OrderAndRemoveUseClauses.php';
	require __DIR__ . '/Additionals/OnlyOrderUseClauses.php';
	require __DIR__ . '/Additionals/OrderMethod.php';
	require __DIR__ . '/Additionals/OrderMethodAndVisibility.php';
	require __DIR__ . '/Additionals/PHPDocTypesToFunctionTypehint.php';
	require __DIR__ . '/Additionals/PrettyPrintDocBlocks.php';
	require __DIR__ . '/Additionals/PSR2EmptyFunction.php';
	require __DIR__ . '/Additionals/PSR2MultilineFunctionParams.php';
	require __DIR__ . '/Additionals/ReindentAndAlignObjOps.php';
	require __DIR__ . '/Additionals/ReindentSwitchBlocks.php';
	require __DIR__ . '/Additionals/RemoveIncludeParentheses.php';
	require __DIR__ . '/Additionals/RemoveSemicolonAfterCurly.php';
	require __DIR__ . '/Additionals/RemoveUseLeadingSlash.php';
	require __DIR__ . '/Additionals/ReplaceBooleanAndOr.php';
	require __DIR__ . '/Additionals/ReplaceIsNull.php';
	require __DIR__ . '/Additionals/RestoreComments.php';
	require __DIR__ . '/Additionals/ReturnNull.php';
	require __DIR__ . '/Additionals/ShortArray.php';
	require __DIR__ . '/Additionals/SmartLnAfterCurlyOpen.php';
	require __DIR__ . '/Additionals/SortUseNameSpace.php';
	require __DIR__ . '/Additionals/SpaceAroundControlStructures.php';
	require __DIR__ . '/Additionals/SpaceAfterExclamationMark.php';
	require __DIR__ . '/Additionals/SpaceAroundExclamationMark.php';
	require __DIR__ . '/Additionals/SpaceAroundParentheses.php';
	require __DIR__ . '/Additionals/SpaceBetweenMethods.php';
	require __DIR__ . '/Additionals/StrictBehavior.php';
	require __DIR__ . '/Additionals/StrictComparison.php';
	require __DIR__ . '/Additionals/StripExtraCommaInArray.php';
	require __DIR__ . '/Additionals/StripNewlineAfterClassOpen.php';
	require __DIR__ . '/Additionals/StripNewlineAfterCurlyOpen.php';
	require __DIR__ . '/Additionals/StripNewlineWithinClassBody.php';
	require __DIR__ . '/Additionals/StripSpaces.php';
	require __DIR__ . '/Additionals/StripSpaceWithinControlStructures.php';
	require __DIR__ . '/Additionals/TightConcat.php';
	require __DIR__ . '/Additionals/TrimSpaceBeforeSemicolon.php';
	require __DIR__ . '/Additionals/UpgradeToPreg.php';
	require __DIR__ . '/Additionals/WordWrap.php';
	require __DIR__ . '/Additionals/WrongConstructorName.php';
	require __DIR__ . '/Additionals/YodaComparisons.php';

	if (!isset($inPhar)) {
		$inPhar = false;
	}
	if (!isset($testEnv)) {
		require __DIR__ . '/cli-core.php';
	}
}
