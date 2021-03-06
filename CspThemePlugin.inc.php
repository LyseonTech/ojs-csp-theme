<?php
import('lib.pkp.classes.plugins.ThemePlugin');
class CspThemePlugin extends ThemePlugin {

    /**
     * Carrega os estilos personalizados de nosso tema
     * @return null
     */
    public function init() {
        
        //$this->addStyle('stylesheet', 'styles/index.less');
        $this->setParent('bootstrapthreethemeplugin');
        //$this->modifyStyle('stylesheet', array('addLess' => array('styles/index.less')));
        $this->addStyle('child-stylesheet', 'styles/index.less');

		HookRegistry::register ('TemplateManager::display', array($this, 'loadTemplateData'));

    }

    /**
     * Obtem o nome de exibição deste tema
     * @return string
     */
    function getDisplayName() {
        return __('plugins.themes.csp.name');
    }

    /**
     * Obtem a descrição deste plugin
     * @return string
     */
    function getDescription() {
        return __('plugins.themes.csp.description');
    }


	public function loadTemplateData($hookName, $args) {

        $request = Application::getRequest();
		$context = $request->getContext();
		$requestPath = $request->getRequestPath();
		$baseUrl = $request->getBaseUrl();
		$router = $request->getRouter();
		$page = $router->_page;

		$params = array(
			'contextId' => $context->getId(),
			'orderBy' => 'seq',
			'orderDirection' => 'ASC',
			'count' => 1,
			'offset' => 0,
			'isPublished' => true,
        );        

		$issues = iterator_to_array(Services::get('issue')->getMany($params));
		$coverImageUrl = $issues[0]->getLocalizedCoverImageUrl();
		$coverImageAltText = $issues[0]->getLocalizedCoverImageAltText();

		$templateMgr = $args[0];
        $templateMgr->assign(array(
			'issues' => $issues,
			'requestPath' => $requestPath,
			'baseUrl' => $baseUrl,
			'page' => $page,
			'coverImageUrl' => $coverImageUrl,
			'coverImageAltText' => $coverImageAltText,
		)); 
	}
}
