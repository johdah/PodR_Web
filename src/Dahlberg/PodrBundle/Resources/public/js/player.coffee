class Player
  audio = new Audio
  currentEpisode = -1

  constructors: ->

  loadPlayer: ->
    console.log "Player - Loading"
    #loadEpisode()

    $(".play-btn").on "click", ->
      id = $(this).attr('id').replace(/[^0-9]/g, '')
      console.log "Playing episode " + id
      loadEpisode id

    # Forward
    $("#player-forward").on "click", (e) ->
      e.preventDefault()
      forwardAudio()

    $("#player-fast-forward").on "click", (e) ->
      e.preventDefault()
      fastForwardAudio()

    # Play/Pause
    $("#player-pause").on "click", (e) ->
      e.preventDefault()
      pauseAudio()

    $("#player-play").on "click", (e) ->
      e.preventDefault()
      playAudio()

    # Rewind
    $("#player-fast-rewind").on "click", (e) ->
      e.preventDefault()
      fastRewindAudio()

    $("#player-rewind").on "click", (e) ->
      e.preventDefault()
      rewindAudio()

    # Stop
    $("#player-stop").on "click", (e) ->
      e.preventDefault()
      stopAudio()

  # Events
  timeUpdate = ->
    currentTime = parseInt(audio.currentTime, 10)
    maxTime = audio.duration
    currentPercentage = parseInt((currentTime/maxTime)*101)
    $("#player-seekbar .meter").css "width", currentPercentage + "%"

  # Loaders
  loadEpisode = (id) ->
    currentEpisode = id
    jqxhr = $.getJSON("/api/v1/episode/" + id, ->
    ).done((data)->
      console.log "Loading: " + data["episode"]["enclosure_url"]
      audio.setAttribute "src", data["episode"]["enclosure_url"]
      audio.setAttribute "type", data["episode"]["enclosure_type"]
    ).fail(->
      console.log "Player - Error occured while loading episode"
    ).always(->
      audio.load()
      #console.log "Player - Load Episode"
      #audio.setAttribute "src", "http://sverigesradio.se/topsy/ljudfil/4794211.mp3"
      #audio.setAttribute "type", "audio/mp3"
      #audio.load()

      # Event: TimeUpdate
      $(audio).bind "timeupdate", ->
        timeUpdate()

      playAudio()
    )

  # Actions
  fastRewindAudio = ->
    audio.currentTime -= 60.0

  fastForwardAudio = ->
    audio.currentTime += 60.0

  forwardAudio = ->
    audio.currentTime += 10.0

  rewindAudio = ->
    audio.currentTime -= 10.0

  pauseAudio = ->
    audio.pause()
    console.log "Player - Pause"
    $("#player-pause").hide()
    $("#player-play").show()
    #updateCurrentTimeOnServer()

  playAudio = ->
    audio.play()
    console.log "Player - Play"
    #$("#player-seekbar").attr "aria-valuemax", audio.duration
    $("#player-pause").show()
    $("#player-play").hide()

  stopAudio = ->
    audio.pause()
    audio.currentTime = 0
    console.log "Player - Stop"
    #currentTime = 0
    $("#player-pause").hide()
    $("#player-play").show()
    #updateCurrentTimeOnServer()

$(document).ready ->
  console.log "Player - Creating"
  player = new Player
  player.loadPlayer()