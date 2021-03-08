<?php

namespace App\Command;

use App\DatabaseWriter;
use App\StockEntryValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\CsvParser;

use function file_get_contents;
use function implode;

class CSVParseCommand extends Command
{
    protected static $defaultName = "run";

    protected function configure()
    {
        $this
            ->addArgument("filename", InputArgument::REQUIRED)
            ->addOption("test");
    }

    private function outputLines(OutputInterface $output, array $lines) {
        foreach ($lines as $line) {
            $fields = [
                $line->productCode ?? "",
                $line->productName ?? "",
                $line->productDescription ?? "",
                $line->stock ?? "",
                $line->cost ?? "",
                $line->discontinued ?? "",
            ];

            $output->writeln(implode(",", $fields));
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filename = $input->getArgument("filename");
        $testOption = $input->getOption("test");

        $csv = @file_get_contents($filename);

        if ($csv === false) {
            $output->writeln("No such file exists");
            return Command::FAILURE;
        }

        $parser = new CsvParser();
        $validator = new StockEntryValidator();

        $rows = $parser->parse($csv);
        $validator->validate($rows);
        $validLines = $validator->getLines();
        $invalidLines = $validator->getInvalidLines();

        if($testOption) {
            $output->writeln("Valid entries");
            $this->outputLines($output, $validLines);
        }

        DatabaseWriter::writeToDatabase($validLines);

        $output->writeln("Invalid entries");
        $this->outputLines($output, $invalidLines);

        return Command::SUCCESS;
    }
}