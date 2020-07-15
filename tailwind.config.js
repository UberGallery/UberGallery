module.exports = {
    purge: [
        // 'app/**/*.php',
        // 'resources/**/*.js',
        // 'resources/**/*.scss',
        // 'resources/**/*.vue',
        // 'views/**/*.twig'
    ],
    theme: {
        extend: {
            fontFamily: {
                poiretone: ['Poiret One', 'sans-serif']
            },
            inset: { '-2': '-2px' },
            spacing: {
                '100': '100%'
            },
            textColor: {
                github: '#171515',
                spectrum: '#7B16FF',
                twitter: '#1DA1F2'
            }
        }
    },
    variants: {
        inset: ['responsive', 'hover']
    },
    plugins: []
};
