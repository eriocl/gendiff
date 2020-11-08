<a href="https://codeclimate.com/github/eriocl/gendiff/maintainability"><img src="https://api.codeclimate.com/v1/badges/f5f9efdaab8ac1ffbb28/maintainability" /></a>
<a href="https://codeclimate.com/github/eriocl/gendiff/test_coverage"><img src="https://api.codeclimate.com/v1/badges/f5f9efdaab8ac1ffbb28/test_coverage" /></a>
<a href="https://github.com/eriocl/gendiff/actions?query=workflow%3A%22PHP+CI%22"><img src="https://github.com/eriocl/brain-games/workflows/PHP%20CI/badge.svg" /></a>

Description:

    This is cli utilite. It shows difference between two files (json or yaml).
    Output result can be presented in 3 formats:     
        pretty
        plain
        json

Setup:

    Install :       composer require eriocl\gendiff 
                                or                     
                    composer global require eriocl\gendiff  
                   
Usage:
  
    gendiff (-h|--help)
  
    gendiff (-v|--version)
  
    gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
 
    -h --help             Show this screen.
  
    -v --version          Show version.
  
    --format <fmt>        Report format [default: pretty]
  
Example:      

    Pretty format: 
    https://asciinema.org/a/vGb2fkDmT5XtrdCVcJb5lGETB

    Plain format:
    https://asciinema.org/a/DZSBocPPZjggMuE8XO6CnU9FH
    
    Json format:
    https://asciinema.org/a/GzNdBcPYSDP5Twm1OXcwsfV6e
