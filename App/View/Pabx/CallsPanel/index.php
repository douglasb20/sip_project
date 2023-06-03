<?php $this->captureStart("css") ?>
<style>
.text-title{
    font-size   : 18px;
    font-weight : 500;
    color       : #060816;
    font-family : "Poppins", sans-serif;
}
.extensionsSip{
    height: 120px;
    cursor: default;
}

.extensionsSip.wait{
    background-color: #D6E4FF;
    border: 1px #ADC8FF solid;
    color: #1939B7 ;
}   
.extensionsSip.own{
    background-color: #FAD6FF;
    border: 1px #F0ADFF solid;
    color: #6A19B7;
}
.extensionsSip.calling{
    background-color: #FEFED2;
    border: 1px #F8F51F solid;
    color: #777405;
}
.extensionsSip.offline{
    background-color: #FFC2AC;
    border: 1px #FF9982 solid;
    color: #B71831;
}

.extensionsSip.onCall{
    background-color: #B1EAFE;
    border: 1px #8BD8FD solid;
    color: #1F62B3
}
.extensionsSip.ringing{
    background-color: #FFE9B2;
    border: 1px #FFDA8B solid;
    color: #B76E1F
}
.colunas{
    width: 220px;
    max-width: 250px;
    margin: 5px;
    border-radius: 5px;
}
.baloes{
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
}


</style>

<?php $this->captureEnd("css") ?>

<?php $this->captureStart("body") ?>

<div class="col-12 ">
    <div class="card ">
        <div class="card-header d-flex flex-row justify-content-between align-items-center">
            <span class="text-title">Painel de Ligações</span>
            <span class="alert-server text-danger"><span class='badge text-bg-danger rounded-circle'>&nbsp</span></span>
        </div>
        <div class="card-body mt-4 ">
            <div class="baloes">
            <?php
                foreach($sip as $s){
                    $own = false;
                    if(GetSessao("ramal") === $s['id_sip']){
                        $own = true;
                    }
            ?>
                <div class="colunas extensionsSip px-3 pt-2 pb-2 sip-<?=$s['id_sip']?> <?=($own ? 'own' : 'wait')?> offline" >
                    <div class="d-flex flex-column align-items-center justify-content-start">
                        <span class="text-xl">
                            <?=$s['id_sip']?>
                        <?php
                            if($own){
                                echo "<i class='fa-regular fa-badge-check'></i>";
                            }
                        ?>
                        </span>
                        <small><?=$s['callerId']?></small>
                        <div class="text-sm">
                            <span class="state">Sem ligações</span>
                            <span class="callDuration"></span>
                        </div>
                    </div>
                </div>
            <?php
                }
            ?>
            </div>
        </div>
    </div>
</div>

<?php $this->captureEnd("body") ?>

<?php $this->captureStart("js") ?>
<script>
    
var socket       = null
let tryReconnect = null;
let timers       = [];

$(function(){
    ConnectToWS()
})

function ConnectToWS() {
    try{

        socket          = new WebSocket('ws://localhost:8080');
        let alertServer = $(".alert-server");
    
        socket.onopen = function(event) {
            try{
                alertServer.html("<span class='badge text-bg-success rounded-circle'>&nbsp</span>");
                alertServer.removeClass("text-danger").addClass("text-success");
                clearInterval(tryReconnect);
                PeersInfo();

            }catch(error){
                console.log("Erro to open: ", error);
            }
        };
        
        socket.onerror = function(error) {
            alertServer.text('Erro do WebSocket: ' + error);
            alertServer.addClass("text-danger").removeClass("text-success");

            if (!socket.CLOSED ) {
                socket.close()
            }
        };
    
        socket.onclose = function(event) {
            alertServer.html("<span class='badge text-bg-danger rounded-circle'>&nbsp</span>");
            alertServer.addClass("text-danger").removeClass("text-success")
            clearInterval(tryReconnect);
            $(".extensionsSip")
            .addClass("offline")
            .find(".state")
            .text("Off-Line");
            tryReconnect = setInterval(ConnectToWS,5000)
        };
    
        socket.onmessage = function (event) {
    
            const accepts = [
                "Newchannel",
                "NewCallerid",
                "DialState",
                "Newstate",
                "DialBegin",
                "DialEnd",
                "Hangup",
                "BridgeEnter",
                "BridgeLeave",
                "CoreShowChannel",
                "PeerStatus",
                "PeerEntry",
                "PeerlistComplete",
            ];
            
            let eventos = JSON.parse(event.data);
            
            if (accepts.includes(eventos.Event)) {
                let evt = eventos;
                let caller;
                let dst;
                
                switch (evt.Event){
                    case "DialBegin":
                        caller = evt.CallerIDNum;
                        dst    = evt.DestCallerIDNum;
    
                        var qdCaller = document.querySelector(`.sip-${caller}`);
                        // let qdDst = document.querySelector(`#${dst}`);
                        if (qdCaller) {
                            qdCaller.classList.remove(...["onCall","riging"]);
                            qdCaller.classList.add("calling");
                            document.querySelector(`.sip-${caller} .state`).innerHTML = `Ligando para ${dst}`
                        }
    
                    break;

                    case "Hangup":
                    case "BridgeLeave":
                        caller = evt.CallerIDNum;
                        
                        qdCaller = document.querySelector(`.sip-${caller}`);
                        if (qdCaller) {
                            qdCaller.classList.remove(...["ringing","calling","onCall"]);
                            document.querySelector(`.sip-${caller} .state`).innerHTML = `Sem Ligação`
                            document.querySelector(`.sip-${caller} .callDuration`).innerHTML = ``
                            
                            clearInterval(timers[`sip${caller}`]); // Para o primeiro intervalo após 5 segundos
                        }
    
    
                    break;

                    case "BridgeEnter":
                        caller = evt.CallerIDNum;
                        dst = evt.ConnectedLineNum

                        qdCaller = document.querySelector(`.sip-${caller}`);
                        if (qdCaller) {
                            qdCaller.classList.remove(...["ringing","calling"]);
                            qdCaller.classList.add("onCall");
    
                            let segundos = 0;
                            document.querySelector(`.sip-${caller} .state`).innerHTML = `${dst}:`;
                            document.querySelector(`.sip-${caller} .callDuration`).innerHTML = secToTime(segundos);
                            let intervalId = setInterval(function() {
                                document.querySelector(`.sip-${caller} .callDuration`).innerHTML = secToTime(++segundos);
                            },1E3)
    
                            timers[`sip${caller}`] = intervalId;
                        }
    
                    break;

                    case "Newstate":
                        caller   = evt.CallerIDNum;
                        dst      = evt.ConnectedLineNum
                        qdCaller = document.querySelector(`.sip-${caller}`);
    
                        if (qdCaller) {
                            if (evt.ChannelStateDesc === "Ringing") {
                                
                                qdCaller.classList.remove(...["onCall","calling"]);
                                qdCaller.classList.add("ringing");
                                document.querySelector(`.sip-${caller} .state`).innerHTML = `Recebendo ligação de ${dst}`
                                TocaRing()
                            }
                        }
                    break;

                    case "CoreShowChannel":
                        GetCoreInfo(evt)
                    break;

                    case "PeerStatus":
                        peer  = evt.Peer.replace("SIP/","");
                        qdSip = $(`.sip-${peer}`);

                        if(qdSip){
                            if(evt.PeerStatus === "Registered"){
                                qdSip
                                .removeClass("offline")
                                .find(".state")
                                .text("Sem ligações")
                            }
                            if(evt.PeerStatus === "Unregistered"){
                                qdSip
                                .addClass("offline")
                                .find(".state")
                                .text("Off-Line");
                            }
                        }
                    break;

                    case "PeerEntry":
                        peer  = evt.ObjectName;
                        qdSip = $(`.sip-${peer}`);
                        
                        if(qdSip){
                            if(evt.Status.includes("OK")){

                                qdSip.removeClass("offline");
                                qdSip.find(".state").text("Sem ligações")
                            }
                            if(evt.Status === "UNKNOWN"){
                                qdSip.addClass("offline");
                                qdSip.find(".state").text("Off-Line")
                            }
                        }
                    break;
                    
                    case "PeerlistComplete":
                        SipsInfo();
                    break;

                    default:
                    break;
                }
            }
    
        };
    }catch(error){
        console.log(error)
    }

    function SipsInfo(){
        socket.send(JSON.stringify(
            {
                Action   : "CoreShowChannels",
            }
        ))
    }

    function PeersInfo(){
        socket.send(JSON.stringify(
            {
                Action   : "SIPpeers",
            }
        ))
    }

    function GetCoreInfo(even){
        
        let channel = even.Channel;
        channel     = channel.replace(channel.slice(-9),"");
        channel     = channel.replace("SIP/","");

        switch(even.Application){
            case "AppDial":
                let caller = even.ConnectedLineNum;
                let dst    = document.querySelector(`.sip-${channel}`);
                
                switch(even.ChannelStateDesc){

                    case "Ringing":
                        if(dst){
                            dst.classList.remove(...["onCall","calling"]);
                            dst.classList.add("ringing");
                            document.querySelector(`.sip-${channel} .state`).innerHTML = `Recebendo ligação de ${caller}`
                        }
                    break;

                    case "Up":

                        if(dst){
                            dst.classList.remove(...["ringing","calling"]);
                            dst.classList.add("onCall");

                            let segundos = tempoParaSegundos(even.Duration);
                            document.querySelector(`.sip-${channel} .state`).innerHTML = `${caller}:`;
                            document.querySelector(`.sip-${channel} .callDuration`).innerHTML = even.Duration;
                            let intervalId = setInterval(function() {
                                document.querySelector(`.sip-${channel} .callDuration`).innerHTML = secToTime(++segundos);
                            },1E3)

                            timers[`sip${channel}`] = intervalId;
                        }
                    break;

                }
            break;

            case "Dial":
                let qdCaller = document.querySelector(`.sip-${channel}`);
                let dstCall    = even.ConnectedLineNum;
                
                switch(even.ChannelStateDesc){

                    case "Ringing":
                        document.querySelector(`.sip-${channel} .state`).innerHTML = `Recebendo ligação de ${dstCall}`
                    break;
                    case "Ring":
                        if(qdCaller){
                            qdCaller.classList.remove(...["onCall","riging"]);
                            qdCaller.classList.add("calling");
                            document.querySelector(`.sip-${channel} .state`).innerHTML = `Ligando para ${dstCall}`
                        }
                    break;

                    case "Up":

                        if(qdCaller){
                            qdCaller.classList.remove(...["ringing","calling"]);
                            qdCaller.classList.add("onCall");

                            let segundos = tempoParaSegundos(even.Duration);
                            document.querySelector(`.sip-${channel} .state`).innerHTML = `${dstCall}:`;
                            document.querySelector(`.sip-${channel} .callDuration`).innerHTML = even.Duration;
                            let intervalId = setInterval(function() {
                                document.querySelector(`.sip-${channel} .callDuration`).innerHTML = secToTime(++segundos);
                            },1E3)

                            timers[`sip${channel}`] = intervalId;
                        }
                    break;
                }
            break;
        }
    }

    async function TocaRing(){
        var audio = await new Audio('/assets/audios/ring.wav');
        audio.play();
    }
}
</script>

<?php $this->captureEnd("js") ?>