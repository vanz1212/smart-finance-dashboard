@extends('layouts.app')

@section('title', 'Stata-like Analysis')

@section('content')
    @php
        $commandGroups = [
            'Data management' => [
                'use', 'save', 'import excel', 'import delimited', 'export excel', 'describe', 'codebook',
                'list', 'browse', 'generate', 'replace', 'egen', 'recode', 'encode', 'decode',
                'rename', 'label variable', 'label define', 'keep', 'drop', 'sort', 'bysort',
                'merge', 'append', 'reshape', 'collapse', 'duplicates', 'compress',
            ],
            'Statistik deskriptif' => [
                'summarize', 'tabulate', 'table', 'tabstat', 'mean', 'ci', 'correlate', 'pwcorr',
                'centile', 'proportion', 'ratio', 'total',
            ],
            'Grafik dan eksplorasi' => [
                'histogram', 'kdensity', 'scatter', 'twoway', 'graph box', 'graph bar',
                'graph pie', 'lowess', 'lfit', 'qnorm', 'pnorm',
            ],
            'Regresi dan ekonometrika' => [
                'regress', 'areg', 'qreg', 'ivregress', 'logit', 'logistic', 'probit', 'ologit',
                'mlogit', 'poisson', 'nbreg', 'glm', 'tobit', 'heckman', 'sureg', 'gmm',
                'sem', 'margins', 'predict', 'estimates', 'estat',
            ],
            'Panel dan time series' => [
                'xtset', 'xtdescribe', 'xtsum', 'xttab', 'xtreg', 'xtlogit', 'xtprobit',
                'xtpoisson', 'xtabond', 'tsset', 'tsline', 'arima', 'var', 'svar', 'vec',
                'dfuller', 'pperron', 'varsoc', 'irf',
            ],
            'Survey, survival, meta, dan power' => [
                'svyset', 'svy:', 'stset', 'sts', 'stcox', 'streg', 'meta set',
                'meta summarize', 'meta regress', 'meta forestplot', 'power', 'bayes:',
            ],
        ];

        $testGroups = [
            'Uji beda rata-rata dan proporsi' => [
                ['command' => 'ttest', 'name' => 'uji t satu sampel, dua sampel, atau berpasangan'],
                ['command' => 'prtest', 'name' => 'uji proporsi satu atau dua sampel'],
                ['command' => 'sdtest', 'name' => 'uji kesamaan varians'],
                ['command' => 'robvar', 'name' => 'uji varians robust antar grup'],
                ['command' => 'bitest', 'name' => 'uji binomial'],
            ],
            'Uji nonparametrik' => [
                ['command' => 'signrank', 'name' => 'Wilcoxon matched-pairs signed-rank test'],
                ['command' => 'ranksum', 'name' => 'Wilcoxon rank-sum atau Mann-Whitney test'],
                ['command' => 'kwallis', 'name' => 'Kruskal-Wallis equality-of-populations rank test'],
                ['command' => 'nptrend', 'name' => 'uji trend nonparametrik'],
                ['command' => 'ksmirnov', 'name' => 'Kolmogorov-Smirnov test'],
            ],
            'Uji normalitas dan distribusi' => [
                ['command' => 'swilk', 'name' => 'Shapiro-Wilk normality test'],
                ['command' => 'sfrancia', 'name' => 'Shapiro-Francia normality test'],
                ['command' => 'sktest', 'name' => 'skewness and kurtosis normality test'],
                ['command' => 'qnorm', 'name' => 'diagnostik normal quantile plot'],
                ['command' => 'pnorm', 'name' => 'diagnostik standardized normal probability plot'],
            ],
            'Uji kategori dan asosiasi' => [
                ['command' => 'tabulate, chi2', 'name' => 'Pearson chi-squared test'],
                ['command' => 'tabulate, exact', 'name' => 'Fisher exact test'],
                ['command' => 'mcc', 'name' => 'matched case-control statistics'],
                ['command' => 'kappa', 'name' => 'interrater agreement'],
                ['command' => 'symmetry', 'name' => 'symmetry and marginal homogeneity tests'],
            ],
            'Uji model regresi' => [
                ['command' => 'test', 'name' => 'Wald test untuk koefisien'],
                ['command' => 'testparm', 'name' => 'uji gabungan beberapa parameter'],
                ['command' => 'lrtest', 'name' => 'likelihood-ratio test'],
                ['command' => 'estat hettest', 'name' => 'Breusch-Pagan atau Cook-Weisberg heteroskedasticity test'],
                ['command' => 'estat imtest', 'name' => 'information matrix test'],
                ['command' => 'estat vif', 'name' => 'diagnostik multikolinearitas'],
            ],
            'Uji ANOVA, panel, dan time series' => [
                ['command' => 'anova', 'name' => 'analysis of variance'],
                ['command' => 'oneway', 'name' => 'one-way ANOVA dan Bartlett test'],
                ['command' => 'manova', 'name' => 'multivariate analysis of variance'],
                ['command' => 'hausman', 'name' => 'Hausman specification test'],
                ['command' => 'xttest0', 'name' => 'Breusch-Pagan LM test untuk random effects'],
                ['command' => 'dfuller', 'name' => 'Augmented Dickey-Fuller unit-root test'],
                ['command' => 'vecrank', 'name' => 'Johansen tests for cointegration'],
            ],
        ];
    @endphp

    <section class="page-section stata-page">
        <div class="section-header stata-header">
            <span class="eyebrow">Command reference</span>
            <h1>Stata-like Analysis</h1>
            <p>Daftar command dan uji statistik Stata resmi yang paling sering dipakai untuk analisis data ekonomi, keuangan, sosial, dan riset kuantitatif.</p>
        </div>

        <div class="stata-summary">
            <div>
                <span>Command resmi</span>
                <strong>{{ collect($commandGroups)->flatten()->count() }}+</strong>
            </div>
            <div>
                <span>Kelompok analisis</span>
                <strong>{{ count($commandGroups) }}</strong>
            </div>
            <div>
                <span>Daftar uji</span>
                <strong>{{ collect($testGroups)->flatten(1)->count() }}+</strong>
            </div>
        </div>

        <div class="stata-section-title">
            <h2>Daftar Command Resmi Stata</h2>
            <p>Command dikelompokkan berdasarkan workflow analisis, mulai dari manajemen data sampai model statistik lanjutan.</p>
        </div>

        <div class="stata-command-grid">
            @foreach ($commandGroups as $group => $commands)
                <article class="stata-command-card">
                    <h3>{{ $group }}</h3>
                    <div class="command-chip-list">
                        @foreach ($commands as $command)
                            <code>{{ $command }}</code>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        <div class="stata-section-title">
            <h2>Daftar Uji Statistik</h2>
            <p>Ringkasan uji yang umum dipanggil lewat command Stata untuk hipotesis, diagnosis model, panel, dan time series.</p>
        </div>

        <div class="stata-test-list">
            @foreach ($testGroups as $group => $tests)
                <article class="stata-test-panel">
                    <h3>{{ $group }}</h3>
                    <div class="stata-test-table">
                        @foreach ($tests as $test)
                            <div class="stata-test-row">
                                <code>{{ $test['command'] }}</code>
                                <span>{{ $test['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>

        <div class="stata-source-note">
            <strong>Sumber resmi:</strong>
            <span>Disusun dari dokumentasi dan fitur resmi Stata.</span>
            <a href="https://www.stata.com/features/" target="_blank" rel="noopener">Stata features</a>
            <a href="https://www.stata.com/manuals/i.pdf" target="_blank" rel="noopener">Stata manuals index</a>
        </div>
    </section>
@endsection
