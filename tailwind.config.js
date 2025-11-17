module.exports = {
    content: [
        "./views/**/*.php",
        "./public/**/*.{js,css}",
    ],
    theme: {
        extend: {
            backgroundImage: {
                'gradient-to-br': 'linear-gradient(to bottom right, var(--tw-gradient-stops))',
            },
            colors: {
                gray: {
                    900: '#0f172a',
                },
                purple: {
                    800: '#6b21a8',
                },
                orange: {
                    800: '#9a3412',
                },
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
}