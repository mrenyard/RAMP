      <figure class="logo">
        <a tabindex="-1" href="/" rel="home"><img src="//media.<?=\ramp\SETTING::$RAMP_DOMAIN; ?>/logo.svg" alt=""></a>
        <figcaption><a tabindex="-1" href="/" rel="home">[LOGO]<sup>TM</sup></a></figcaption>
      </figure>
      <nav>
        <ul id="quick-links">
          <li><a href="https://google.com/accessibility#main" title="Interacting with, accessing and getting around this site">Accessibility</a></li>
          <li><a href="#main" title="Skip to Main content: <?=$this->title; ?>">Main Content</a></li>
          <li><a href="#site-nav" title="Jump to Full Site Map (Navigation)">Site Navigation</a></li>
        </ul>
        <form id="quick-search" method="get" action="/search#results"><label for="query">Search</label>
          <div class="search input field" title="Search <?=\ramp\SETTING::$RAMP_DOMAIN; ?> for the latest content, ...">
            <label for="query">Search</label>
            <input id="query" name="query" type="search" tabindex="0" placeholder="[PLACEHOLDER]" required="required" pattern="[a-zA-Z _\-:]*">
            <span class="hint">Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit tempore cum debitis voluptatem alias quos esse nostrum illo repudiandae incidunt. Magnam dolorum iste libero esse odit sit harum corrupti dolore? Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi aliquam, voluptates ad ex ab laborum eveniet quia eos quibusdam deserunt similique ducimus molestias corporis odit ratione laudantium quis nesciunt? Ullam.</span>
          </div>            
          <input type="submit" value="Go">
        </form>
      </nav>
