<?php
require 'config.php';

$articles = load_json('articles.json');
$sectors = load_json('sectors.json');

usort($articles, function ($a, $b) {
    $dateA = strtotime($a['date'] ?? '1970-01-01');
    $dateB = strtotime($b['date'] ?? '1970-01-01');

    return $dateB <=> $dateA;
});

$pageTitle = 'Αρχική';

require 'header.php';
?>
<section class="hero">
    <div>
        <span class="eyebrow">ΦΥΣΙΚΗ ΓΙΑ ΟΛΟΥΣ</span>

        <h1>
            Η έρευνα της Φυσικής,<br>
            <em>με απλά λόγια.</em>
        </h1>

        <p>
            Ανακαλύψεις, πειράματα και ιδέες από όλες τις περιοχές
            της σύγχρονης Φυσικής.
        </p>
    </div>

    <div class="physics-visual" aria-hidden="true">
        <div class="physics-core">
            <span class="physics-letter">Φ</span>
            <span class="physics-label">ΦΥΣΙΚΗ</span>
        </div>
    </div>
</section>
<section class="filters"><input id="q" type="search" placeholder="Αναζήτηση άρθρων…"><div class="chips"><button class="active" data-sector="">Όλα</button><?php foreach($sectors as $s):?><button data-sector="<?=esc($s)?>"><?=esc($s)?></button><?php endforeach?></div></section>
<section><div class="section-head"><div><span class="eyebrow">ΠΡΟΣΦΑΤΑ</span><h2>Άρθρα και ιστορίες</h2></div><span id="count"><?=count($articles)?> άρθρα</span></div><div class="grid" id="grid">
<?php foreach($articles as $a):?><article class="card" data-sector="<?=esc($a['sector'])?>" data-search="<?=esc(mb_strtolower($a['title'].' '.$a['excerpt'].' '.$a['author'],'UTF-8'))?>"><a href="article.php?id=<?=urlencode($a['id'])?>"><?php if(!empty($a['image'])):?><img src="<?=esc($a['image'])?>" alt=""><?php else:?><div class="placeholder">⚛</div><?php endif?><div class="card-body"><span class="tag"><?=esc($a['sector'])?></span><h3><?=esc($a['title'])?></h3><p><?=esc($a['excerpt'])?></p><div class="meta"><span><?=esc($a['author'])?></span><time><?=date('d.m.Y',strtotime($a['date']))?></time></div></div></a></article><?php endforeach?></div><div id="empty" class="empty" hidden>Δεν βρέθηκαν άρθρα.</div></section>
<script>const q=document.querySelector('#q'),cards=[...document.querySelectorAll('.card')],buttons=[...document.querySelectorAll('.chips button')],count=document.querySelector('#count');let sector='';function filter(){let term=q.value.toLocaleLowerCase('el');let n=0;cards.forEach(c=>{let ok=(!sector||c.dataset.sector===sector)&&c.dataset.search.includes(term);c.hidden=!ok;if(ok)n++});count.textContent=n+' άρθρα';document.querySelector('#empty').hidden=n!==0}q.oninput=filter;buttons.forEach(b=>b.onclick=()=>{buttons.forEach(x=>x.classList.remove('active'));b.classList.add('active');sector=b.dataset.sector;filter()});</script>
<?php require 'footer.php'; ?>