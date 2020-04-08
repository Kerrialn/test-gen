<?php


namespace App;


use _HumbugBox09702017065e\Roave\BetterReflection\Identifier\Identifier;
use PhpParser\Builder\Method;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter;

class Generator
{
    const PROJECT_FILES_PATH = __DIR__ . '/../src/';
    const TEST_FILES_PATH = __DIR__ . '/../tests/';


    public function getProjectFiles() : Finder
    {
        $finder = new Finder();
        $filter = function (\SplFileInfo $file)
        {
            $file->getExtension() == 'php' ? true : false;
        };

        return $finder->files()->filter($filter)->in(self::PROJECT_FILES_PATH);
    }

    public function generate()
    {

        $finder = $this->getProjectFiles();

        foreach ($finder->files() as $file) {
            try {


                $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);


                ini_set('xdebug.max_nesting_level', 3000);
                $content = $parser->parse($file->getContents());

                $traverser = new NodeTraverser();




                $traverser->addVisitor(new class extends NodeVisitorAbstract {


                    public function enterNode(Node $node)
                    {
                        $factory = new BuilderFactory;

                        if ($node instanceof Node\Stmt\Class_) {


                            $node->name = new Node\Identifier($node->name . 'Test');
                            $node->extends = New Node\Identifier('TestCase');
                            $node->flags = 1;
                        }


                        if ($node instanceof Node\Stmt\ClassMethod) {

                            $testMethod = new Node\Stmt\ClassMethod( New Node\Identifier('Test'));
                            $testMethod->flags = 1;

                            $node->stmts[] = $testMethod;

                        }

                    }
                });

                $ast = $traverser->traverse($content);

                $prettyPrinter = new PrettyPrinter\Standard;
                $outputFileContent = $prettyPrinter->prettyPrintFile($ast);

                $filesystem = new Filesystem();

                $filesystem->dumpFile(self::TEST_FILES_PATH . $file->getRelativePath() . '/' . $file->getFilenameWithoutExtension() . 'Test' . '.php', $outputFileContent);

            } catch (\PhpParser\Error $e) {
                echo 'Parse Error: ', $e->getMessage();
            }

        }


    }

}


