<?php

namespace diff\gendiff;

function run()
{
    \Docopt::handle(DOC);
}

CONST DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)

Options:
  -h --help                     Show this screen
  -v --version                  Show version

DOC;


