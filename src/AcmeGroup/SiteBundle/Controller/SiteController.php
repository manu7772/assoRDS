<?php

namespace AcmeGroup\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use AcmeGroup\LaboBundle\Entity\demandedevis;
// classes paramètres
use AcmeGroup\LaboBundle\Classes\paramEvenement;

class SiteController extends Controller {

	//////////////////////////
	// PAGES
	//////////////////////////

	public function indexAction() {
		$this->get('acmeGroup.aelog')->createNewLogAuto();
		// $data = $this->get("session")->get('version');
		// génération de la page
		// $mailer = $this->get('mailer');
		// $message = \Swift_Message::newInstance()
		// 	->setSubject("Test de mail")
		// 	->setContentType('text/html')
		// 	->setFrom('noreply@asso.com')
		// 	->setTo('manu7772@gmail.com')
		// 	->setBody("bonjour");
		// $rmail = $mailer->send($message);
		return $this->render($this->verifVersionPage("Site:index"));
	}

	public function homepageAction() {
		return $this->indexAction();
	}

	public function pagewebAction($categorieSlug = "web", $pagewebSlug = null, $pagedata = null) {
		$this->get('acmeGroup.aelog')->createNewLogAuto();
		// JSon décode et vérifie si c'était bien des données au format JSon (sinon les gardes telles quelles)
		$pd = json_decode($pagedata, true);
		if($pd !== null) $pagedata = $pd;
		// si la page appélée vient d'une catégorie (et non d'une pageweb)
		// et qu'aucune pageweb n'est précisée : on retrouve la pageweb de la catégorie
		if(($categorieSlug !== "web") && ($pagewebSlug === null)) {
			// récupération de $pagewebSlug selon $categorieSlug
			$categorie = $this->get("acmeGroup.categorie")->getRepo()->findBySlug($categorieSlug);
			if(count($categorie) > 0) {
				$pagewebSlug = $categorie[0]->getPage()->getSlug();
			} else {
				$$categorieSlug = null;
			}
		}
		return $this->dispatch($pagewebSlug, $pagedata);
	}

	/**
	 * detailEventAction
	 * Page de détails sur un évènement $eventSlug
	 * @param string $eventSlug
	 * @param string $categorieSlug
	 * @return Response
	 */
	public function detailEventAction($eventSlug, $categorieSlug = null) {
		// Récupération de l'actu
		$event = $this->get("acmeGroup.entities")->defineEntity("evenement")->getRepo()->findBySlug($eventSlug);
		if(count($event) > 0) {
			$data['event'] = $event[0];
		} else $data['event'] = null;
		// génération de la page
		return $this->render($this->verifVersionPage("pageweb:detailEvent"), $data);
	}

	/**
	 * valeursAction
	 * Page de détails sur un évènement $eventSlug
	 * @param string $eventSlug
	 * @param string $categorieSlug
	 * @return Response
	 */
	public function valeursAction($categorieSlug = null) {
		$pageweb = $this->get("acmeGroup.pageweb")->defineEntity("pageweb");
		$page = $pageweb->getRepo()->findBySlug("l-association");
		$data['page'] = $page[0];
		// génération de la page
		return $this->render($this->verifVersionPage($data['page']->getFichierhtml()), $data);
	}

	public function equipeAction($categorieSlug = null) {
		$data = array();
		$data['categorie'] = $this->get("acmeGroup.categorie")->getRepo()->findBySlug($categorieSlug);
		$data['page'] = $data['categorie']->getPage();
		if(is_object($data['page'])) {
			$html = $data['page']->getFichierhtml();
			// données sur l'équipe
			switch ($categorieSlug) {
				case 'ensemble':
					$data['data'] = null;
					break;
				case 'trombinoscope':
					$data['data'] = null;
					break;
				case 'bureau':
					$data['data'] = null;
					break;
				case 'benevoles':
					$data['data'] = null;
					break;
				default: // "notre-equipe" et autres…
					$data['data'] = null;
					break;
			}
		} else {
			// page non trouvée : page par défaut (no-page)
			$page = $this->get("acmeGroup.pageweb")->getRepo()->findBySlug("no-page");
			$data['page'] = $page[0];
			$html = $data['page']->getFichierhtml();
		}
		// génération de la page
		return $this->render($this->verifVersionPage($html), $data);
	}

	public function partenairesAction($partenaireSlug) {
		$page = $this->get("acmeGroup.pageweb")->getRepo()->findBySlug("nos-partenaires");
		if($partenaireSlug === "liste") {
			// liste des partenaires
			$data["partenaires"] = $this->get("acmeGroup.entities")->defineEntity('partenaire')->getRepo()->findAll();
			$data["partenaire"] = "no";
		} else {
			$data["partenaires"] = "no";
			$part = $this->get("acmeGroup.entities")->defineEntity('partenaire')->getRepo()->findBySlug($partenaireSlug);
			if(count($part) > 0) {
				// un partenaire
				$data["partenaire"] = $part[0];
			} else {
				// partenaire(s) non trouvé(s)
				$data["partenaire"] = $data["partenaires"] = "no";
			}
		}
		$data['page'] = $page[0];
		// génération de la page
		return $this->render($this->verifVersionPage($data['page']->getFichierhtml()), $data);
	}



	protected function dispatch($pagewebSlug, $pagedata = null) {
		$data = array();
		$Tidx = $this->get("session")->get('version');
		$page = $this->get("acmeGroup.pageweb")->getDynPages($pagewebSlug);
		if(count($page) > 0) {
			// page existe
			$data['pageweb'] = $page[0];
			switch ($pagewebSlug) {
				case 'contact':
					$data['societe'] = $this->get("acmeGroup.version")->getRepo()->find($Tidx['id']);
					break;
				case 'actualites':
					// $pagedata = null
					// $pagedata['limit'] = nombre d'actualités demandées (null par défaut = toutes)
					// $pagedata['sens'] = 'ASC' ou 'DESC' ('DESC' par défaut)
					// *** $pagedata['datedebut'] = datetime date (null par défaut = toutes)
					// *** $pagedata['datefin'] = datetime date (null par défaut = toutes)
					$limit = null;
					if(isset($pagedata['limit']) && $pagedata['limit'] !== null) {
						$limit = intval($pagedata['limit']);
					}
					$sens = 'DESC';
					if(isset($pagedata['sens'])) {
						if(in_array(strtoupper($pagedata['sens']), array("ASC", "DESC"))) {
							$sens = strtoupper($pagedata['sens']);
						}
					}
					$data['events'] = $this->get("acmeGroup.events")->getRepo()->findFuturs('actualites', $sens, $limit);
					break;
				case 'nos-partenaires':
					$data["partenaires"] = $this->get("acmeGroup.entities")->defineEntity('partenaire')->getRepo()->findAll();
					break;
				case 'un-partenaire':
					// $pagedata = slug du partenaire
					$part = $this->get("acmeGroup.entities")->defineEntity('partenaire')->getRepo()->findBySlug($pagedata);
					if(count($part) > 0) {
						reset($part);
						$data["partenaire"] = current($part);
					} else $data["partenaire"] = false;
					break;
				
				default:
					// pages par défaut / Dont celles qui n'ont pas besoin de données
					// $page = $this->get("acmeGroup.pageweb")->getDynPages("homepage");
					// $data['pageweb'] = $page[0];
					break;
			}
		} else {
			// page n'existe pas => no-page
			$page = $this->get("acmeGroup.pageweb")->getDynPages("no-page");
			$data['pageweb'] = $page[0];
		}
		return $this->render($this->verifVersionPage($data['pageweb']->getFichierhtml()), $data);
	}


	//////////////////////////
	// BLOCS
	//////////////////////////

	public function menuAction($template = "menuPpal") {
		// génération de la page
		return $this->render($this->verifVersionPage("menu:".$template));
	}

	public function carrousselAction($collection = null) {
		$data['carroussel'] = $this->get('acmeGroup.collection')->getDiaporama($collection);
		// génération de la page
		return $this->render($this->verifVersionPage("blocs:carroussel"), $data);
	}

	public function newshomeAction($date = null) {
		$ent = $this->get('acmeGroup.events');
		$data['newshome'] = $ent->getNewshome($date);
		// génération de la page
		return $this->render($this->verifVersionPage("blocs:newshome"), $data);
	}

	public function boutondonAction($date = null) {
		// génération de la page
		return $this->render($this->verifVersionPage("blocs:boutondon"));
	}



	//////////////////////////
	// RSS
	//////////////////////////

	public function getRssAction() {
		$data["evenement"] = $this->get('acmeGroup.events')->getNEventsByType(array("actions"), 1000, false);
		$xmlContent = $this->renderView("AcmeGroupSiteBundle:rss:flux001.xml.twig", $data);
		$response = new Response($xmlContent);
		$response->headers->set('Content-Type', 'xml');
		return $response;
	}

	public function generateRss() {
		$data = array();
		$rss = $this->renderView("AcmeGroupSiteBundle:rss:flux001.xml.twig", $data);
		$f = fopen("/web/images/testfichier.rss", "a");
		fwrite($f, $rss);
		fclose($f);
	}


	//////////////////////////
	// Autres fonctions
	//////////////////////////

	private function recupVersion() {
		$Tidx = $this->get("session")->get('version');
		if($Tidx["templateIndex"] === null) $Tidx["templateIndex"] = "Site";
		return $Tidx;
	}

	private function verifVersionPage($page) {
		$p = explode(":", $page, 2);
		if(count($p) > 1) {
			$Tidx["templateIndex"] = $p[0];
			$page = $p[1];
		} else {
			$Tidx = $this->recupVersion();
		}
		if(!$this->get('templating')->exists("AcmeGroupSiteBundle:".$Tidx["templateIndex"].":".$page.".html.twig")) {
			// si la page n'existe pas, on prend le template de la version par défaut
			$Tidx["templateIndex"] = $this->get("acmeGroup.version")->getDefaultVersionDossTemplates();
		}
		return "AcmeGroupSiteBundle:".$Tidx["templateIndex"].":".$page.".html.twig";
	}


}



