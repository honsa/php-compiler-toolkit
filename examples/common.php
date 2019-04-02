<?php

require __DIR__ . '/../vendor/autoload.php';

use PHPCompilerToolkit\Backend;
use PHPCompilerToolkit\Context;
use PHPCompilerToolkit\Printer;

function compile(Context $context): array {
    $libjit = new Backend\LIBJIT;
    $libgccjit = new Backend\LIBGCCJIT;
    $llvm = new Backend\LLVM;

    $timers = [];

    $results = [];
    $start = microtime(true);

    $results['libjit'] = $libjit->compile($context, Backend::O3);
    $timers["libjit"] = microtime(true);
     
    $results['libgccjit'] = $libgccjit->compile($context, Backend::O3);
    $timers['libgccjit'] = microtime(true);

    $results['llvm'] =  $llvm->compile($context, Backend::O3);
    $timers['llvm'] = microtime(true);

    echo "Time to Compile: \n";
    foreach ($timers as $name => $time) {
        printf("%10s: %2.6F seconds\n", $name, $time - $start);
        $start = $time;
    }
    echo "\n";
    return $results;
}

function writeResultsFiles(Context $context, array $results, string $dir): void {
    file_put_contents($dir . '/example.ir', (new Printer)->print($context));
    foreach ($results as $name => $result) {
        $result->dumpToFile($dir . '/' . $name . '.bc');
        $result->dumpCompiledToFile($dir . '/' . $name . '.s');
    }
}

function getCallbacks(array $results, string $cbname): array {
    $callbacks = [];
    foreach ($results as $name => $result) {
        $callbacks[$name] = $result->getCallable($cbname);
    }
    return $callbacks;
}

function testCall(array $callbacks, string $cbname, array ...$argSets): void {
    echo "\nTesting $cbname:\n";
    foreach ($callbacks as $compiler => $callback) {
        echo "  Compiler $compiler\n";
        foreach ($argSets as $args) {
            echo "    " . renderCall($cbname, $args) . " = " . doCall($callback, $args) . "\n";
        }
    }
    echo "\n";
}

function renderCall(string $cbname, array $args): string {
    $params = [];
    foreach ($args as $arg) {
        if (is_array($arg)) {
            $params[] = renderCall($cbname, $arg);
        } else {
            $params[] = $arg;
        }
    }
    return $cbname . '(' . implode(', ', $params) . ')';
}

function doCall(callable $cb, array $args) {
    $params = [];
    foreach ($args as $arg) {
        if (is_array($arg)) {
            $params[] = doCall($cb, $arg);
        } else {
            $params[] = $arg;
        }
    }
    return $cb(...$params);
}

function benchmark(array $callbacks, callable $closureBaseline, int $times, array $args) {
    echo "Benchmarking\n";
    $timers = [];
    $start = microtime(true);
    foreach ($callbacks as $compiler => $callable) {
        for ($i = 0; $i < $times; $i++) {
            $callable(...$args);
        }
        $timers[$compiler] = microtime(true);
    }
    for ($i = 0; $i < $times; $i++) {
        $closureBaseline(...$args);
    }
    $timers["php closure"] = microtime(true);

    echo "Done\n\nBenchmark Results:\n";
    foreach ($timers as $compiler => $time) {
        printf("    %12s: %2.5F seconds\n", $compiler, $time - $start);
        $start = $time;
    }
    echo "\n";
}

function generateResults(Context $context, string $dir, string $fnName, array $tests, int $iterations, callable $baseline) {
    $results = compile($context);
    writeResultsFiles($context, $results, $dir);
    $callbacks = getCallbacks($results, $fnName);

    testCall($callbacks, $fnName, ...$tests);

    benchmark($callbacks, $baseline, $iterations, $tests[0]);
}