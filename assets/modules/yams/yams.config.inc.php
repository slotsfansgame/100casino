<?php
  // The ids of the active languages
  $this->itsActiveLangIds = array(
    'en'
    , 'ru'
  );
  // The ids of the inactive languages
  $this->itsInactiveLangIds = array(
    'fr'
    , 'ja'
    , 'de'
  );
  // The language direction (ltr or rtl)
  $this->itsIsLTR = array(
    'en' => TRUE
    , 'fr' => TRUE
    , 'ja' => TRUE
    , 'de' => TRUE
    , 'ru' => TRUE
  );
  // The default language id
  $this->itsDefaultLangId = 'ru';
  // The name of the root folder
  $this->itsRootName = array(
    'en' => 'en'
    , 'fr' => 'fr'
    , 'ja' => 'ja'
    , 'de' => 'de'
    , 'ru' => 'ru'
  );
  // The monolingual page server name
  $this->itsMonoServerName = '';
  // The server name for each language
  $this->itsMultiServerName = array(
    'en' => ''
    , 'fr' => ''
    , 'ja' => ''
    , 'de' => ''
    , 'ru' => ''
  );
  // The name of each language in all languages
  $this->itsLangNames = array(
    'en' => array(
      'en' => 'English'
      , 'fr' => '(French)'
      , 'ja' => '(Japanese)'
      , 'de' => '(German)'
      , 'ru' => '(Russian)'
    )
    , 'fr' => array(
      'en' => '(Anglais)'
      , 'fr' => 'Français'
      , 'ja' => '(Japonais)'
      , 'de' => '(Allemand)'
      , 'ru' => '(Russe)'
    )
    , 'ja' => array(
      'en' => '（英語）'
      , 'fr' => '（フランス語）'
      , 'ja' => '日本語'
      , 'de' => '（ドイツ語）'
      , 'ru' => '（ロシア語）'
    )
    , 'de' => array(
      'en' => '(Englisch)'
      , 'fr' => '(Französisch)'
      , 'ja' => '(Japanisch)'
      , 'de' => 'Deutsch'
      , 'ru' => '(Russisch)'
    )
    , 'ru' => array(
      'en' => '(Английский)'
      , 'fr' => '(Французский)'
      , 'ja' => '(Японский)'
      , 'de' => '(Немецкий)'
      , 'ru' => 'Русский'
    )
  );
  $this->itsChooseLangText = array(
    'en' => 'Select language'
    , 'fr' => 'Choisir une langue'
    , 'ja' => '言語選択'
    , 'de' => 'Waehle Sprache'
    , 'ru' => 'Выберите язык'
    );
  // The languages that should be directed to this language root.
  // These should be in priority order
  // The tag is in the format provided by the HTTP Accept-Language header:
  // xx, or xx-yy, where
  // xx: is a two letter language abbreviation
  //     http://www.loc.gov/standards/iso639-2/php/code_list.php
  // yy: is a two letter country code
  //     http://www.iso.org/country_codes/iso_3166_code_lists/english_country_names_and_code_elements.htm
  // xx on its own matches an xx Accept-Language header
  // with any country code
  // At least one language tag must be specified.
  $this->itsLangTags = array(
    'en' => array(
      'en' 
      )
    , 'fr' => array(
      'fr' 
      )
    , 'ja' => array(
      'ja' 
      )
    , 'de' => array(
      'de' 
      )
    , 'ru' => array(
      'ru' 
      )
    );
  // The MODx manager language name for each language group.
  $this->itsMODxLangName = array(
    'en' => 'english'
    , 'fr' => 'francais-utf8'
    , 'ja' => 'japanese-utf8'
    , 'de' => 'german'
    , 'ru' => 'russian-UTF8'
    );
  // The encoding modifier.
  // 'manager' means use the manager setting
  // 'u' if webpage content is in UTF-8
  // '' otherwise
  $this->itsEncodingModifierMode = 'manager';
  // a comma separated list of active template ids
  // if the default activity is none
  $this->itsActiveTemplates = array(
      );
  // Whether or not to manage template variables automatically
  $this->itsManageTVs = TRUE;
  // The yams current lang query parameter name
  $this->itsLangQueryParam = 'yams_lang';
  // The yams change lang query parameter name
  $this->itsChangeLangQueryParam = 'yams_new_lang';
  // Turn on/off redirection from existing pages to multilingual pages
  // You can set to false if you are developing a site from scratch
  // - although leaving as TRUE does not harm in this instance
  // Set to TRUE if you are converting a website
  // that has already been made public
  $this->itsRedirectionMode = 'default';
  // The type of http redirection to perform when redirecting to the default language
  $this->itsHTTPStatus = 307;
  // The type of http redirection to perform when redirecting to a non-default language
  $this->itsHTTPStatusNotDefault = 303;
  // The type of http redirection to perform when responding to a request to change language
  $this->itsHTTPStatusChangeLang = 303;
  // Whether or not to hide the original fields
  $this->itsHideFields = FALSE;
  // Whether or not to place tvs for individual languages on separate tabs
  $this->itsTabifyLangs = TRUE;
  // Whether or not to synchronise the document pagetitle with the default language pagetitle
  $this->itsSynchronisePagetitle = FALSE;
  // Whether or not to to use EasyLingual Compatiblity Mode
  $this->itsEasyLingualCompatibility = FALSE;
  // Whether or not to show the site_start document alias.
  $this->itsShowSiteStartAlias = TRUE;
  // Whether or not to rewrite containers as folders.
  $this->itsRewriteContainersAsFolders = FALSE;
  // If MODx is installed into a subdirectory then this param
  // can be used to specify the path to that directory.
  // (with a trailing slash and no leading slash)
  $this->itsMODxSubdirectory = '';
  // The URL conversion mode
  // none: Don't do any automatic conversion of MODx URLs.
  // default: Convert MODx URLs surrounded by double quotes to (yams_doc:id) placeholders
  // resolve: Convert MODx URLs surrounded by double quotes to (yams_docr:id) placeholders
  $this->itsURLConversionMode = 'default';
  // Whether or not to use multilingual aliases.
  $this->itsUseMultilingualAliases = FALSE;
  // Whether or not multilingual aliases are unique.
  $this->itsMultilingualAliasesAreUnique = FALSE;
  // Whether or not to use mime-dependent URL suffixes.
  $this->itsUseMimeDependentSuffixes = FALSE;
  // The mime-type to suffix mapping.
  $this->itsMimeSuffixMap = array(
    'application/xhtml+xml' => '.xhtml'
    , 'application/javascript' => '.js'
    , 'text/javascript' => '.js'
    , 'application/rss+xml' => '.rss'
    , 'application/xml' => '.xml'
    , 'text/xml' => '.xml'
    , 'text/css' => '.css'
    , 'text/html' => '.html'
    , 'text/plain' => '.txt'
    );
  // A mapping from langIds to roles.
  // Says which roles have access to each language.
  // If an empty string is provided all roles have access
  // If no key is provided for a language all roles have access
  $this->itsLangRolesAccessMap = array(
    );
  // Whether or not to use stripAlias on multilingual aliases.
  $this->itsUseStripAlias = TRUE;
  // An array of doc ids for which URLs of the form index.php?id= ... will be  // accepted - even if friendly aliases are being used.
  // A * entry means all docIds.
  $this->itsAcceptMODxURLDocIds = array(
    );
?>