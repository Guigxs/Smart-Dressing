<?PHP

// src/Controller/Test.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class Test extends AbstractController
{
    /**
     * @Route("/test", name="test_list")
     */
    public function list()
    {
        $number = random_int(0, 5);

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}

?>