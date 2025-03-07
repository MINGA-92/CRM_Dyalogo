
// Aqui van las configuraciones y variables globales del flujograma
const config = {
    font: '16px FontAwesome',
    colores: {
        green: '#84D94C',
        yellow: '#FFCC00',
        blue : '#00CCFF',
        orange : '#FF6600',
        red: '#DD1C1A',
        purple: '#9211F2',
        grey: '#88888E',
        active: '#28a745',
        disabled: '#dddddd'
    },
    colorIcono: {
        white: '#FFF',
        lightText: 'whitesmoke'
    },
    iconos:{
        cargador: '\uf093',
        webform: '\uf2d0',
        leadsCorreo: '\uf658',
        webservice: '\uf1e6',
        chatWeb: '\uf075',
        whatsapp: '\uf232',
        facebook: '\uf09a',
        correoEntrante: '\uf0e0',
        smsEntrante: '\uf3cd',
        bot: '\uf544',
        ia: '\ue1ec',
        campanaEntrante: '\uf78c',
        campanaSaliente: '\uf31e',
        backoffice: '\uf2c1',
        correoSaliente: '\uf0e0',
        smsSaliete: '\uf3cd',
        instagram: '\uf16d',
        cagueManual: '\uf56f',
        salMRobotico: '\uf095',
        calidad: '\uf1c7',
        LlamadasM: '\uf095',
        IVRs: '\uf0cb'
    },
    iconosBot: {
        bienvenida: '\uf075',
        despedida: '\uf4b3',
        conversacional: '\uf086',
        transaccional: '\uf0cb'
    },
    iconosIVR: {
        InicioIVR: '\uf2a0',
        NumeroExterno: '\uf095',
        Campana: '\uf0c0',
        Grabacion: '\uf130',
        Audio: '\uf028',
        OtroIVR:  '\uf0cb',
        Encuesta: '\uf15c',
        Digitos:  '\uf0e8',
        FinalIVR: '\uf3dd',
        Avanzado: '\uf544',
        //Bomb:  '\uf1e2',
    },
    nombrePasos: {
        cargador: 'Cargador',
        webform: 'Formulario Web',
        comWebform: 'Webform Entrante',
        leadsCorreo: 'Leads de correos',
        webservice: 'Webservice',
        chatWeb: 'Chat web',
        whatsapp: 'Whatsapp',
        salWhatsapp: 'Plantilla Whatsapp',
        facebook: 'Facebook messenger',
        correoEntrante: 'Correo entrante',
        smsEntrante: 'Sms entrante',
        bot: 'Bot',
        ia: 'IA',
        campanaEntrante: 'Campaña entrante',
        campanaSaliente: 'Campaña saliente',
        backoffice: 'Tareas de backoffice',
        correoSaliente: 'Correo saliente',
        smsSaliete: 'Sms saliente',
        instagram: 'Instagram',
        cagueManual: 'Cargue manual',
        salMRobotico: "Marcador Robotico",
        calidad: 'Tareas de calidad',
        LlamadasM: 'Llamadas Entrantes',
        IVRs: "IVR's"
    },
    nombreSeccionesBot:{
        bienvenida: 'Bienvenida',
        despedida: 'Despedida',
        conversacional: 'Conversacional',
        transaccional: 'Transaccional'
    },
    nombreSeccionesIVR:{
        InicioIVR: 'Inicio IVR',
        TomaDigitos: 'Captura Respuesta',
        NumeroExterno: 'Numero Externo',
        Campana: 'Pasar a Campaña',
        Grabacion: 'Reproducir Grabación',
        OtroIVR: 'Pasar a Otro IVR',
        Encuesta: 'Pasar a Encuesta',
        Avanzado: 'Avanzado',
        FinalIVR: 'Final IVR'
    }
};
