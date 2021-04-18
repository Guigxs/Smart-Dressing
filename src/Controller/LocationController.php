<?PHP

// src/Controller/LocationController
namespace App\Controller;

use App\Entity\Cloth;
use App\Entity\Category;
use App\Entity\Wardrobe;
use App\Entity\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Annotation\Route;


class LocationController extends AbstractController
{

    /**
     * @Route("/api/location/get", name="locationController_getLocation")
     */
    public function getLocation(){
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();
        return $this->json($locations, 200, [], ["groups"=>["show_location"]]);
    }
    /**
     * @Route("/api/location/update", name="locationController_updateLocation")
     */
    public function updateLocation(string $country, string $city){
        $entityManager = $this->getDoctrine()->getManager();

        $location = new Location();
        $location->setCountry($country);
        $location->setCity($city);

        $entityManager->persist($location);
        $entityManager->flush();

        return $this->redirect("/");
    }

    /**
     * @Route("/api/location/remove", name="locationController_removeLocation")
     */
    public function removeLocation(){
        $entityManager = $this->getDoctrine()->getManager();
        $locations = $this->getDoctrine()->getRepository(Location::class)->findAll();

        foreach ($locations as $entity) {
            $entityManager->remove($entity);
        }

        $entityManager->flush();

        return $this->redirect("/");
    }
}
?>